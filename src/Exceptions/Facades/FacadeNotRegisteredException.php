<?php

namespace Skay1994\MyFramework\Exceptions\Facades;

class FacadeNotRegisteredException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}