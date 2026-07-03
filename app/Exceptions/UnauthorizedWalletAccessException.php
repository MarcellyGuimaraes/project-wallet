<?php

namespace App\Exceptions;

class UnauthorizedWalletAccessException extends WalletException
{
    public function __construct(string $message = 'Você não tem permissão para operar sobre esta carteira ou transação.')
    {
        parent::__construct($message);
    }
}
