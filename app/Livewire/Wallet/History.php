<?php

namespace App\Livewire\Wallet;

use App\Exceptions\WalletException;
use App\Models\Transaction;
use App\Services\Wallet\ReversalService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class History extends Component
{
    use WithPagination;

    public ?string $errorMessage = null;

    public ?string $successMessage = null;

    public function reverse(int $transactionId, ReversalService $reversalService): void
    {
        $this->errorMessage = null;
        $this->successMessage = null;

        $transaction = Transaction::findOrFail($transactionId);

        try {
            $reversalService->reverse(Auth::user(), $transaction, 'Estorno solicitado pelo usuário.');
        } catch (WalletException $e) {
            $this->errorMessage = $e->getMessage();

            return;
        }

        $this->successMessage = 'Transação revertida com sucesso.';
    }

    public function render()
    {
        $wallet = Auth::user()->wallet;

        $transactions = Transaction::where('from_wallet_id', $wallet->id)
            ->orWhere('to_wallet_id', $wallet->id)
            ->latest()
            ->paginate(10);

        return view('livewire.wallet.history', [
            'wallet' => $wallet,
            'transactions' => $transactions,
        ]);
    }
}
