<?php

namespace Tests\Unit\Services;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Exceptions\InvalidAmountException;
use App\Models\User;
use App\Services\Wallet\DepositService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepositServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_deposit_increases_wallet_balance(): void
    {
        $user = User::factory()->create();

        $transaction = (new DepositService)->deposit($user, '100.00');

        $this->assertSame('100.00', $user->wallet->fresh()->balance);
        $this->assertSame(TransactionType::Deposit, $transaction->type);
        $this->assertSame(TransactionStatus::Completed, $transaction->status);
        $this->assertSame($user->wallet->id, $transaction->to_wallet_id);
    }

    public function test_deposit_heals_a_negative_balance(): void
    {
        $user = User::factory()->create();
        $user->wallet()->update(['balance' => '-30.00']);

        (new DepositService)->deposit($user, '50.00');

        $this->assertSame('20.00', $user->wallet->fresh()->balance);
    }

    public function test_deposit_rejects_zero_or_negative_amounts(): void
    {
        $user = User::factory()->create();

        $this->expectException(InvalidAmountException::class);

        (new DepositService)->deposit($user, '0');
    }
}
