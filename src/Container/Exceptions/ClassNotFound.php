<?php

namespace Skay1994\MyFramework\Container\Exceptions;

use Exception;

class ClassNotFound extends Exception
{
    public function __construct(string $class)
    {
        $message = "Service Container: Class [$class] not found";
        $code = 500;

        parent::__construct($message, $code);
    }
}