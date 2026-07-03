<?php

namespace App\Services\Wallet;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Exceptions\TransactionNotReversibleException;
use App\Exceptions\UnauthorizedWalletAccessException;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class ReversalService
{
    public function reverse(User $requester, Transaction $transaction, ?string $reason = null): Transaction
    {
        $this->authorize($requester, $transaction);

        if (! $transaction->isReversible()) {
            throw new TransactionNotReversibleException;
        }

        return DB::transaction(function () use ($transaction, $reason) {
            $walletIds = array_filter([$transaction->from_wallet_id, $transaction->to_wallet_id]);

            $wallets = Wallet::whereIn('id', $walletIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // Re-check status under lock to guard against a concurrent double reversal.
            $lockedTransaction = Transaction::whereKey($transaction->id)->lockForUpdate()->firstOrFail();

            if (! $lockedTransaction->isReversible()) {
                throw new TransactionNotReversibleException;
            }

            if ($lockedTransaction->to_wallet_id) {
                $toWallet = $wallets->get($lockedTransaction->to_wallet_id);
                $toWallet->update(['balance' => bcsub($toWallet->balance, $lockedTransaction->amount, 2)]);
            }

            if ($lockedTransaction->from_wallet_id) {
                $fromWallet = $wallets->get($lockedTransaction->from_wallet_id);
                $fromWallet->update(['balance' => bcadd($fromWallet->balance, $lockedTransaction->amount, 2)]);
            }

            $lockedTransaction->update(['status' => TransactionStatus::Reversed]);

            return Transaction::create([
                'type' => TransactionType::Reversal,
                'status' => TransactionStatus::Completed,
                'amount' => $lockedTransaction->amount,
                'from_wallet_id' => $lockedTransaction->to_wallet_id,
                'to_wallet_id' => $lockedTransaction->from_wallet_id,
                'original_transaction_id' => $lockedTransaction->id,
                'description' => $reason,
            ]);
        });
    }

    private function authorize(User $requester, Transaction $transaction): void
    {
        $ownedWalletId = $requester->wallet->id;

        $isOwner = $transaction->from_wallet_id === $ownedWalletId
            || $transaction->to_wallet_id === $ownedWalletId;

        if (! $isOwner) {
            throw new UnauthorizedWalletAccessException;
        }
    }
}
