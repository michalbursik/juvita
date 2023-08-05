<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class InsufficientAmountException extends Exception
{
    public function __construct(
        $message = "",
        $code = 0,
        Throwable $previous = null)
    {
        $newMessage = 'Množství se kterým se snažíte manipulovat není skladem.';

        parent::__construct($newMessage, $code, $previous);
    }
}
