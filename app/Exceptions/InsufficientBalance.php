<?php

namespace App\Exceptions;

use Exception;

class InsufficientBalance extends Exception
{
    public function __construct($message = 'Insufficient balance to perform the transaction', $code = 422)
    {
        parent::__construct($message, $code);
    }
}
