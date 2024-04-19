<?php

namespace Skay1994\MyFramework;

class Container
{
    public static ?Container $instance = null;

    public static function getInstance(): static
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }
}