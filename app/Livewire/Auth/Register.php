<?php

namespace App\Livewire\Auth;

use App\Rules\CpfOrCnpj;
use App\Services\Auth\RegisterUserService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Register extends Component
{
    public string $name = '';

    public string $email = '';

    public string $document = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $initial_balance = '0';

    public function register(RegisterUserService $registerUserService): void
    {
        $this->document = preg_replace('/\D/', '', $this->document);
        $this->email = mb_strtolower(trim($this->email));

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'document' => ['required', 'string', new CpfOrCnpj, 'unique:users,document'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'initial_balance' => ['nullable', 'numeric', 'min:0'],
        ]);

        $user = $registerUserService->register(
            name: $validated['name'],
            email: $validated['email'],
            document: $validated['document'],
            password: $validated['password'],
            initialBalance: (string) ($validated['initial_balance'] ?: '0'),
        );

        Auth::login($user);

        $this->redirectRoute('dashboard', navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
