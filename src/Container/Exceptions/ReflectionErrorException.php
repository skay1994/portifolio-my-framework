<?php

namespace Skay1994\MyFramework\Container\Exceptions;

class ReflectionErrorException extends \Exception
{
    public function __construct(string $message = "", int $code = 0)
    {
        parent::__construct($message, $code);
    }
}