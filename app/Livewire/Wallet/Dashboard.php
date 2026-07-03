<?php

namespace App\Livewire\Wallet;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        $wallet = Auth::user()->wallet;

        $recentTransactions = Transaction::where('from_wallet_id', $wallet->id)
            ->orWhere('to_wallet_id', $wallet->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('livewire.wallet.dashboard', [
            'wallet' => $wallet,
            'recentTransactions' => $recentTransactions,
        ]);
    }
}
