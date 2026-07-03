<?php

namespace App\Exceptions;

use Exception;

class AdminActionNotAllowedException extends Exception
{
    public function __construct(string $message = 'Esta ação não é permitida.')
    {
        parent::__construct($message);
    }
}
