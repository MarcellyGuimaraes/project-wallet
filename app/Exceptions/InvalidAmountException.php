<?php

namespace App\Exceptions;

class InvalidAmountException extends WalletException
{
    public function __construct(string $message = 'O valor informado é inválido. Utilize um valor positivo.')
    {
        parent::__construct($message);
    }
}
