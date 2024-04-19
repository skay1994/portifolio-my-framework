<?php

namespace Skay1994\MyFramework;

class Application
{
    const VERSION = '0.0.1';

    public function __construct()
    {
    }

    public function run()
    {
        echo $this->helloWorld();
    }

    private function helloWorld()
    {
        return 'My Framework by Jorge Carlos v'.self::VERSION;
    }
}