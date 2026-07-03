<?php

namespace Tests\Feature;

use App\Livewire\Auth\Register;
use App\Livewire\Wallet\Deposit;
use App\Livewire\Wallet\History;
use App\Livewire\Wallet\Transfer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class WalletFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_away_from_the_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_a_user_can_register_and_is_logged_in(): void
    {
        Livewire::test(Register::class)
            ->set('name', 'Ana Silva')
            ->set('email', 'ana@example.com')
            ->set('document', '52998224725')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->set('initial_balance', '25.00')
            ->call('register')
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();

        $user = User::whereEmail('ana@example.com')->firstOrFail();
        $this->assertSame('25.00', $user->wallet->balance);
    }

    public function test_registration_rejects_an_invalid_document(): void
    {
        Livewire::test(Register::class)
            ->set('name', 'Ana Silva')
            ->set('email', 'ana@example.com')
            ->set('document', '11111111111')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('register')
            ->assertHasErrors(['document']);

        $this->assertGuest();
    }

    public function test_a_logged_in_user_can_deposit_transfer_and_reverse(): void
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        $this->actingAs($sender);

        Livewire::test(Deposit::class)
            ->set('amount', '100.00')
            ->call('deposit')
            ->assertHasNoErrors();

        $this->assertSame('100.00', $sender->wallet->fresh()->balance);

        Livewire::test(Transfer::class)
            ->set('recipient_email', $recipient->email)
            ->set('amount', '30.00')
            ->call('transfer')
            ->assertHasNoErrors();

        $this->assertSame('70.00', $sender->wallet->fresh()->balance);
        $this->assertSame('30.00', $recipient->wallet->fresh()->balance);

        $transferTransaction = $sender->wallet->fresh()->outgoingTransactions()->firstOrFail();

        Livewire::test(History::class)
            ->call('reverse', $transferTransaction->id)
            ->assertSet('successMessage', 'Transação revertida com sucesso.');

        $this->assertSame('100.00', $sender->wallet->fresh()->balance);
        $this->assertSame('0.00', $recipient->wallet->fresh()->balance);
    }

    public function test_transfer_to_an_unknown_email_fails_validation(): void
    {
        $sender = User::factory()->create();
        $sender->wallet()->update(['balance' => '50.00']);

        $this->actingAs($sender);

        Livewire::test(Transfer::class)
            ->set('recipient_email', 'nobody@example.com')
            ->set('amount', '10.00')
            ->call('transfer')
            ->assertHasErrors(['recipient_email']);

        $this->assertSame('50.00', $sender->wallet->fresh()->balance);
    }
}
