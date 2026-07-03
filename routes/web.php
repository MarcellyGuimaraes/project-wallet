<?php

use App\Livewire\Admin\Users as AdminUsers;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Home;
use App\Livewire\Wallet\Dashboard;
use App\Livewire\Wallet\Deposit;
use App\Livewire\Wallet\History;
use App\Livewire\Wallet\Transfer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', Register::class)->name('register');
    Route::get('/login', Login::class)->name('login');
});

Route::post('/logout', function () {
    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login');
})->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/wallet/deposit', Deposit::class)->name('wallet.deposit');
    Route::get('/wallet/transfer', Transfer::class)->name('wallet.transfer');
    Route::get('/wallet/history', History::class)->name('wallet.history');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', AdminUsers::class)->name('admin.users');
});
