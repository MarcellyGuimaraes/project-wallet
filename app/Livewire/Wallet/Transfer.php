<?php

namespace App\Livewire\Wallet;

use App\Exceptions\WalletException;
use App\Models\User;
use App\Services\Wallet\TransferService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Transfer extends Component
{
    public string $recipient_email = '';

    public string $amount = '';

    public string $description = '';

    public ?string $successMessage = null;

    public function transfer(TransferService $transferService): void
    {
        $this->successMessage = null;

        $validated = $this->validate([
            'recipient_email' => [
                'required',
                'email',
                'exists:users,email',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if ($value === Auth::user()->email) {
                        $fail('Não é possível transferir para a própria conta.');
                    }
                },
            ],
            'amount' => ['required', 'numeric', 'gt:0'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $recipient = User::where('email', $validated['recipient_email'])->firstOrFail();

        try {
            $transferService->transfer(
                sender: Auth::user(),
                recipient: $recipient,
                amount: (string) $validated['amount'],
                description: $validated['description'] ?: null,
            );
        } catch (WalletException $e) {
            $this->addError('amount', $e->getMessage());

            return;
        }

        $this->reset('recipient_email', 'amount', 'description');
        $this->successMessage = 'Transferência realizada com sucesso.';
    }

    public function render()
    {
        return view('livewire.wallet.transfer', [
            'wallet' => Auth::user()->wallet,
        ]);
    }
}
