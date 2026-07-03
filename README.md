# Wallet — Carteira Financeira

Desafio técnico full stack: uma aplicação web onde usuários se cadastram, autenticam e
realizam depósitos, transferências e estornos, com saldo consistente sob concorrência
e histórico auditável de todas as movimentações.

## Stack e por quê

- **Laravel 13 + Livewire 4 (TALL stack)** — o desafio pede "uma interface funcional",
  não apenas uma API. Livewire permite construir essa interface inteira em PHP/Blade,
  sem duplicar regras de validação e de negócio em um front-end separado, mantendo o
  time de uma pessoa só e o código mais simples de revisar.
- **PostgreSQL** — suporta bem `SELECT ... FOR UPDATE`, essencial para o requisito de
  atomicidade nas operações financeiras (ver [Concorrência e atomicidade](#concorrência-e-atomicidade)).
- **bcmath** — todo valor monetário é tratado como `string` decimal (nunca `float`) e
  somado/subtraído com `bcadd`/`bcsub`/`bccomp`, evitando erros de arredondamento
  binário em dinheiro.
- **Docker** — `docker compose up --build` sobe app + banco sem exigir PHP/Composer/Node
  instalados na máquina do avaliador. Detalhes completos em [GUIA.md](GUIA.md).

## Como rodar

Forma recomendada (única dependência: Docker):

```bash
docker compose up --build
```

Acesse http://localhost:8000. Migrations, chave da aplicação e link de storage são
gerados automaticamente pelo entrypoint. Passo a passo completo, variáveis de
ambiente e modo de desenvolvimento com hot-reload em [GUIA.md](GUIA.md).

Para rodar os testes dentro do container:

```bash
docker compose exec app php artisan test
```

## Modelagem de dados

- **`users`** — dados de cadastro (nome, e-mail, CPF/CNPJ validado por dígito
  verificador, senha).
- **`wallets`** — saldo em uma tabela **separada** do usuário (1:1), para não misturar
  identidade com estado financeiro e deixar explícito que o saldo tem suas próprias
  regras de concorrência.
- **`transactions`** — histórico imutável de toda movimentação: `type` (deposit,
  transfer, reversal), `status` (completed, reversed, failed), `amount`,
  `from_wallet_id`/`to_wallet_id` (nullable — depósito não tem origem) e
  `original_transaction_id`, que liga um estorno à transação que ele desfez. Nenhuma
  linha é apagada ou sobrescrita: reverter marca a original como `reversed` e cria uma
  **nova** transação do tipo `reversal` — o histórico serve como trilha de auditoria.

## Regras de negócio

- **Depósito**: soma ao saldo da própria carteira. Se o saldo estiver negativo, a soma
  simples já "abate" a diferença — não é um caso especial, é a mesma operação.
- **Transferência**: debita o remetente e credita o destinatário na mesma transação de
  banco. Bloqueada se o remetente não tiver saldo suficiente (saldo pode chegar a
  exatamente zero, não abaixo disso).
- **Estorno**: desfaz um depósito ou uma transferência `completed`, devolvendo o valor
  para quem tinha antes. Um estorno **não pode** ser estornado, e uma transação já
  revertida não pode ser revertida de novo. Isso pode, deliberadamente, deixar uma
  carteira negativa — se o dinheiro estornado já tiver sido usado, o requisito do
  desafio já cobre esse caso ("caso o saldo da pessoa esteja negativo por algum
  motivo, no depósito deve acrescentar ao valor").
- **Autorização**: só o dono de uma carteira participante (origem ou destino) pode
  solicitar o estorno de uma transação.

## Concorrência e atomicidade

Toda operação financeira roda dentro de `DB::transaction()` e usa
`lockForUpdate()` nas linhas de carteira envolvidas **antes** de ler o saldo, prevenindo
condições de corrida (ex.: duas transferências simultâneas do mesmo saldo). Quando duas
carteiras são bloqueadas (transferência e estorno de transferência), o lock é sempre
adquirido em ordem crescente de `id`, para que duas operações concorrentes em sentidos
opostos não se enforquem uma na outra (deadlock). Um estorno também relê o status da
transação com lock dentro da transação de banco, para impedir que dois cliques de
"reverter" simultâneos revertam a mesma transação duas vezes.

## Arquitetura

```
app/
├── Enums/                     TransactionType, TransactionStatus
├── Exceptions/                Exceções de domínio (WalletException e subclasses)
├── Livewire/
│   ├── Auth/                  Register, Login
│   └── Wallet/                Dashboard, Deposit, Transfer, History
├── Models/                    User, Wallet, Transaction
├── Rules/                     CpfOrCnpj (validação com dígito verificador)
└── Services/
    ├── Auth/RegisterUserService.php
    └── Wallet/
        ├── DepositService.php
        ├── TransferService.php
        └── ReversalService.php
```

Componentes Livewire são a camada de apresentação: validam entrada e delegam a regra
de negócio a um Service. Cada Service tem uma única responsabilidade (SRP) — depositar,
transferir e estornar não compartilham uma classe "WalletService" genérica, o que torna
cada regra fácil de testar isoladamente e evita que uma mudança em uma operação
arrisque quebrar outra.

## Testes

`tests/Unit/Services` cobre as regras de negócio diretamente (depósito cura saldo
negativo, transferência bloqueia saldo insuficiente, estorno não pode ser duplicado,
autorização de estorno). `tests/Feature/WalletFlowTest.php` exercita o fluxo completo
através dos componentes Livewire (registro, login, depósito, transferência, estorno).

```bash
php artisan test
```

## O que não foi feito

- Verificação de e-mail e recuperação de senha (fora do escopo do desafio).
- Paginação de histórico usa `LatestPagination` simples; não há filtro por tipo/data na UI.
- Observabilidade fica limitada aos logs padrão do Laravel — não há Telescope instalado.
