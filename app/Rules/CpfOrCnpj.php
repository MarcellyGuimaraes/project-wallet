<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class CpfOrCnpj implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $document = preg_replace('/\D/', '', (string) $value);

        $isValid = match (strlen($document)) {
            11 => $this->isValidCpf($document),
            14 => $this->isValidCnpj($document),
            default => false,
        };

        if (! $isValid) {
            $fail('O :attribute informado não é um CPF ou CNPJ válido.');
        }
    }

    private function isValidCpf(string $cpf): bool
    {
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($position = 9; $position <= 10; $position++) {
            $sum = 0;

            for ($i = 0; $i < $position; $i++) {
                $sum += (int) $cpf[$i] * (($position + 1) - $i);
            }

            $checkDigit = ($sum * 10) % 11;
            $checkDigit = $checkDigit === 10 ? 0 : $checkDigit;

            if ($checkDigit !== (int) $cpf[$position]) {
                return false;
            }
        }

        return true;
    }

    private function isValidCnpj(string $cnpj): bool
    {
        if (preg_match('/^(\d)\1{13}$/', $cnpj)) {
            return false;
        }

        $weights = [
            [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2],
            [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2],
        ];

        foreach ($weights as $index => $weightSet) {
            $length = 12 + $index;
            $sum = 0;

            for ($i = 0; $i < $length; $i++) {
                $sum += (int) $cnpj[$i] * $weightSet[$i];
            }

            $checkDigit = $sum % 11;
            $checkDigit = $checkDigit < 2 ? 0 : 11 - $checkDigit;

            if ($checkDigit !== (int) $cnpj[$length]) {
                return false;
            }
        }

        return true;
    }
}
