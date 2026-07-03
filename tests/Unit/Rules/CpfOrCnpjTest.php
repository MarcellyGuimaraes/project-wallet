<?php

namespace Tests\Unit\Rules;

use App\Rules\CpfOrCnpj;
use PHPUnit\Framework\TestCase;

class CpfOrCnpjTest extends TestCase
{
    public function test_accepts_a_valid_cpf(): void
    {
        $this->assertTrue($this->passes('529.982.247-25'));
    }

    public function test_accepts_a_valid_cnpj(): void
    {
        $this->assertTrue($this->passes('11.222.333/0001-81'));
    }

    public function test_rejects_a_cpf_with_invalid_check_digits(): void
    {
        $this->assertFalse($this->passes('529.982.247-26'));
    }

    public function test_rejects_a_repeated_digit_sequence(): void
    {
        $this->assertFalse($this->passes('111.111.111-11'));
    }

    public function test_rejects_a_value_with_an_unexpected_length(): void
    {
        $this->assertFalse($this->passes('12345'));
    }

    private function passes(string $value): bool
    {
        $failed = false;

        (new CpfOrCnpj)->validate('document', $value, function () use (&$failed) {
            $failed = true;
        });

        return ! $failed;
    }
}
