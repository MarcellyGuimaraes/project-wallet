<?php

namespace App\Livewire\Wallet;

use App\Exceptions\WalletException;
use App\Services\Wallet\DepositService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Deposit extends Component
{
    public string $amount = '';

    public string $description = '';

    public ?string $successMessage = null;

    public function deposit(DepositService $depositService): void
    {
        $this->successMessage = null;

        $validated = $this->validate([
            'amount' => ['required', 'numeric', 'gt:0'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $depositService->deposit(
                user: Auth::user(),
                amount: (string) $validated['amount'],
                description: $validated['description'] ?: null,
            );
        } catch (WalletException $e) {
            $this->addError('amount', $e->getMessage());

            return;
        }

        $this->reset('amount', 'description');
        $this->successMessage = 'Depósito realizado com sucesso.';
    }

    public function render()
    {
        return view('livewire.wallet.deposit', [
            'wallet' => Auth::user()->wallet,
        ]);
    }
}
