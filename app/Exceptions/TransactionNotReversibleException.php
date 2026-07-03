<?php

namespace App\Exceptions;

class TransactionNotReversibleException extends WalletException
{
    public function __construct(string $message = 'Esta transação não pode ser revertida.')
    {
        parent::__construct($message);
    }
}
