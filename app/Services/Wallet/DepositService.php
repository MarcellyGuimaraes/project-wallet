<?php

namespace App\Services\Wallet;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Exceptions\InvalidAmountException;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class DepositService
{
    public function deposit(User $user, string $amount, ?string $description = null): Transaction
    {
        if (bccomp($amount, '0', 2) <= 0) {
            throw new InvalidAmountException;
        }

        return DB::transaction(function () use ($user, $amount, $description) {
            $wallet = Wallet::where('user_id', $user->id)->lockForUpdate()->firstOrFail();

            $wallet->update([
                'balance' => bcadd($wallet->balance, $amount, 2),
            ]);

            return Transaction::create([
                'type' => TransactionType::Deposit,
                'status' => TransactionStatus::Completed,
                'amount' => $amount,
                'to_wallet_id' => $wallet->id,
                'description' => $description,
            ]);
        });
    }
}
