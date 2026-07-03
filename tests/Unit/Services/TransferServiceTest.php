<?php

namespace Tests\Unit\Services;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\InvalidAmountException;
use App\Models\User;
use App\Services\Wallet\TransferService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransferServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_transfer_moves_balance_between_wallets(): void
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();
        $sender->wallet()->update(['balance' => '100.00']);

        $transaction = (new TransferService)->transfer($sender, $recipient, '40.00');

        $this->assertSame('60.00', $sender->wallet->fresh()->balance);
        $this->assertSame('40.00', $recipient->wallet->fresh()->balance);
        $this->assertSame(TransactionType::Transfer, $transaction->type);
        $this->assertSame(TransactionStatus::Completed, $transaction->status);
    }

    public function test_transfer_fails_when_sender_has_insufficient_balance(): void
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();
        $sender->wallet()->update(['balance' => '10.00']);

        try {
            (new TransferService)->transfer($sender, $recipient, '40.00');
            $this->fail('Expected InsufficientBalanceException was not thrown.');
        } catch (InsufficientBalanceException) {
            // expected
        }

        $this->assertSame('10.00', $sender->wallet->fresh()->balance);
        $this->assertSame('0.00', $recipient->wallet->fresh()->balance);
    }

    public function test_transfer_rejects_transferring_to_self(): void
    {
        $sender = User::factory()->create();
        $sender->wallet()->update(['balance' => '100.00']);

        $this->expectException(InvalidAmountException::class);

        (new TransferService)->transfer($sender, $sender, '10.00');
    }

    public function test_transfer_allows_balance_to_reach_exactly_zero(): void
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();
        $sender->wallet()->update(['balance' => '50.00']);

        (new TransferService)->transfer($sender, $recipient, '50.00');

        $this->assertSame('0.00', $sender->wallet->fresh()->balance);
        $this->assertSame('50.00', $recipient->wallet->fresh()->balance);
    }
}
