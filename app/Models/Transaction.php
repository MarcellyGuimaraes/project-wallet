<?php

namespace App\Models;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'type',
    'status',
    'amount',
    'from_wallet_id',
    'to_wallet_id',
    'original_transaction_id',
    'description',
])]
class Transaction extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'type' => TransactionType::class,
            'status' => TransactionStatus::class,
            'amount' => 'decimal:2',
        ];
    }

    public function fromWallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'from_wallet_id');
    }

    public function toWallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'to_wallet_id');
    }

    public function originalTransaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'original_transaction_id');
    }

    public function reversal(): HasOne
    {
        return $this->hasOne(Transaction::class, 'original_transaction_id');
    }

    public function isReversible(): bool
    {
        return $this->status === TransactionStatus::Completed
            && $this->type !== TransactionType::Reversal;
    }

    public function isReversibleBy(User $user): bool
    {
        $walletId = $user->wallet?->id;

        $isOwner = $this->from_wallet_id === $walletId || $this->to_wallet_id === $walletId;

        return $isOwner && $this->isReversible();
    }

    public function signedAmountFor(int $walletId): string
    {
        return $this->to_wallet_id === $walletId
            ? bcadd('0', $this->amount, 2)
            : bcsub('0', $this->amount, 2);
    }
}
