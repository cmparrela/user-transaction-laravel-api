<?php

namespace App\Exceptions;

use Exception;

class NotificationFailException extends Exception
{
    public function __construct($message = 'Notification Fail', $code = 500)
    {
        parent::__construct($message, $code);
    }
}
