<?php

namespace App\Exceptions;

class InsufficientBalanceException extends WalletException
{
    public function __construct(string $message = 'Saldo insuficiente para realizar esta operação.')
    {
        parent::__construct($message);
    }
}
