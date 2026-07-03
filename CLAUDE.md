# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Laravel 13 + Livewire 4 (TALL stack) financial wallet app: users register, authenticate,
and perform deposits, transfers, and reversals, with balance consistency under
concurrency and an auditable transaction history. PHP 8.5, PostgreSQL 18 (production),
SQLite in-memory (tests). See README.md for the full domain write-up (data model,
business rules, concurrency strategy) — it's authoritative and detailed; don't duplicate
it here, read it directly when working on wallet logic.

## Commands

The project runs in Docker; there is no requirement to have PHP/Composer/Node on the
host. All app commands below assume the containers are up (`docker compose up -d`
or the dev override, see GUIA.md).

```bash
# Run full test suite
docker compose exec app php artisan test

# Run a single test file / filter by name
docker compose exec app php artisan test tests/Unit/Services/TransferServiceTest.php
docker compose exec app php artisan test --filter=test_name_or_method

# Migrations
docker compose exec app php artisan migrate
docker compose exec app php artisan migrate:fresh

# Tinker REPL
docker compose exec app php artisan tinker

# Code style (Pint, Laravel preset, no custom config)
docker compose exec app ./vendor/bin/pint
```

If working outside Docker with local PHP/Composer/Node installed, the same commands
work without the `docker compose exec app` prefix. Tests run against an in-memory
SQLite DB (configured in `phpunit.xml`), not Postgres — so `lockForUpdate()` calls
execute but SQLite doesn't enforce the same row-locking semantics as Postgres.

Dev mode with hot-reload (Vite HMR + live PHP/Blade reload):

```bash
docker compose -f compose.yaml -f compose.dev.yaml up --build
```

## Architecture

```
app/
├── Enums/                     TransactionType, TransactionStatus
├── Exceptions/                Domain exceptions (WalletException and subclasses)
├── Livewire/
│   ├── Auth/                  Register, Login
│   └── Wallet/                Dashboard, Deposit, Transfer, History
├── Models/                    User, Wallet, Transaction
├── Rules/                     CpfOrCnpj (checksum-digit validation)
└── Services/
    ├── Auth/RegisterUserService.php
    └── Wallet/
        ├── DepositService.php
        ├── TransferService.php
        └── ReversalService.php
```

- **Livewire components are the presentation layer only**: they validate input and
  delegate all business logic to a Service. Each Service has a single responsibility
  (deposit / transfer / reversal are separate classes, not one generic
  `WalletService`) — keep new wallet operations following this split rather than
  adding methods to an existing service.
- **Money is always a decimal `string`, never `float`.** Arithmetic goes through
  `bcadd`/`bcsub`/`bccomp` (bcmath) to avoid binary rounding errors. Any new code
  touching `amount` or balances must follow this convention.
- **Concurrency**: every financial operation runs inside `DB::transaction()` and takes
  `lockForUpdate()` on the involved wallet rows *before* reading the balance. When two
  wallets are locked in the same operation (transfer, reversal of a transfer), locks
  are always acquired in ascending `id` order to avoid deadlocks between concurrent
  operations in opposite directions. Reversals also re-read the transaction's status
  with a lock inside the DB transaction to prevent double-reversal from concurrent
  requests.
- **`transactions` is append-only**: nothing is deleted or overwritten. Reversing a
  transaction marks the original as `reversed` and inserts a *new* `reversal`
  transaction linked via `original_transaction_id`. This is the audit trail — preserve
  this pattern for any new transaction-mutating feature.
- **Wallet balance lives in its own `wallets` table** (1:1 with `users`), intentionally
  separate from user identity.
- A wallet can legitimately go negative after a reversal if the reverted funds were
  already spent elsewhere — this is expected, not a bug; deposits simply add to
  whatever the balance is (negative-balance correction is not a special case).

## Testing

- `tests/Unit/Services` — business rules in isolation (negative-balance deposit
  correction, insufficient-balance transfer blocking, non-duplicable reversal,
  reversal authorization).
- `tests/Feature/WalletFlowTest.php` — full flow through the actual Livewire
  components (register → login → deposit → transfer → reversal).
