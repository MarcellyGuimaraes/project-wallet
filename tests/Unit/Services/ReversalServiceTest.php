<?php

namespace Tests\Unit\Services;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Exceptions\TransactionNotReversibleException;
use App\Exceptions\UnauthorizedWalletAccessException;
use App\Models\User;
use App\Services\Wallet\DepositService;
use App\Services\Wallet\ReversalService;
use App\Services\Wallet\TransferService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReversalServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_reversing_a_deposit_debits_the_wallet(): void
    {
        $user = User::factory()->create();
        $deposit = (new DepositService)->deposit($user, '100.00');

        $reversal = (new ReversalService)->reverse($user, $deposit);

        $this->assertSame('0.00', $user->wallet->fresh()->balance);
        $this->assertSame(TransactionType::Reversal, $reversal->type);
        $this->assertSame(TransactionStatus::Reversed, $deposit->fresh()->status);
    }

    public function test_reversing_a_deposit_can_push_balance_negative_if_funds_were_already_spent(): void
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        $deposit = (new DepositService)->deposit($sender, '100.00');
        (new TransferService)->transfer($sender, $recipient, '100.00');

        // Sender's balance is now 0. Reversing the original deposit must be
        // allowed even though it drives the sender's balance negative.
        (new ReversalService)->reverse($sender, $deposit);

        $this->assertSame('-100.00', $sender->wallet->fresh()->balance);
    }

    public function test_reversing_a_transfer_restores_both_wallets(): void
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();
        $sender->wallet()->update(['balance' => '100.00']);

        $transfer = (new TransferService)->transfer($sender, $recipient, '40.00');

        (new ReversalService)->reverse($sender, $transfer);

        $this->assertSame('100.00', $sender->wallet->fresh()->balance);
        $this->assertSame('0.00', $recipient->wallet->fresh()->balance);
    }

    public function test_a_transaction_cannot_be_reversed_twice(): void
    {
        $user = User::factory()->create();
        $deposit = (new DepositService)->deposit($user, '100.00');

        (new ReversalService)->reverse($user, $deposit);

        $this->expectException(TransactionNotReversibleException::class);

        (new ReversalService)->reverse($user, $deposit->fresh());
    }

    public function test_a_reversal_itself_cannot_be_reversed(): void
    {
        $user = User::factory()->create();
        $deposit = (new DepositService)->deposit($user, '100.00');
        $reversal = (new ReversalService)->reverse($user, $deposit);

        $this->expectException(TransactionNotReversibleException::class);

        (new ReversalService)->reverse($user, $reversal);
    }

    public function test_a_user_cannot_reverse_someone_elses_transaction(): void
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();
        $outsider = User::factory()->create();
        $sender->wallet()->update(['balance' => '100.00']);

        $transfer = (new TransferService)->transfer($sender, $recipient, '40.00');

        $this->expectException(UnauthorizedWalletAccessException::class);

        (new ReversalService)->reverse($outsider, $transfer);
    }
}
