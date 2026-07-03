<?php

namespace App\Services\Wallet;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\InvalidAmountException;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class TransferService
{
    public function transfer(User $sender, User $recipient, string $amount, ?string $description = null): Transaction
    {
        if (bccomp($amount, '0', 2) <= 0) {
            throw new InvalidAmountException;
        }

        if ($sender->is($recipient)) {
            throw new InvalidAmountException('Não é possível transferir para a própria carteira.');
        }

        return DB::transaction(function () use ($sender, $recipient, $amount, $description) {
            $senderWalletId = $sender->wallet->id;
            $recipientWalletId = $recipient->wallet->id;

            // Lock both wallets in a fixed (ascending id) order to prevent
            // deadlocks when two transfers happen concurrently in opposite directions.
            $wallets = Wallet::whereIn('id', [$senderWalletId, $recipientWalletId])
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $senderWallet = $wallets->get($senderWalletId);
            $recipientWallet = $wallets->get($recipientWalletId);

            if (bccomp($senderWallet->balance, $amount, 2) < 0) {
                throw new InsufficientBalanceException;
            }

            $senderWallet->update(['balance' => bcsub($senderWallet->balance, $amount, 2)]);
            $recipientWallet->update(['balance' => bcadd($recipientWallet->balance, $amount, 2)]);

            return Transaction::create([
                'type' => TransactionType::Transfer,
                'status' => TransactionStatus::Completed,
                'amount' => $amount,
                'from_wallet_id' => $senderWallet->id,
                'to_wallet_id' => $recipientWallet->id,
                'description' => $description,
            ]);
        });
    }
}
