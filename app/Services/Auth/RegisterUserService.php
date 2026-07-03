<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterUserService
{
    public function register(string $name, string $email, string $document, string $password, string $initialBalance = '0'): User
    {
        return DB::transaction(function () use ($name, $email, $document, $password, $initialBalance) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'document' => preg_replace('/\D/', '', $document),
                'password' => Hash::make($password),
            ]);

            $user->wallet()->create([
                'balance' => $initialBalance,
            ]);

            return $user;
        });
    }
}
