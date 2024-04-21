<?php

namespace Skay1994\MyFramework\Facades;

use Skay1994\MyFramework\Facade;

/**
 * @method static run()
 */
class App extends Facade
{
    protected static function name(): string
    {
        return 'app';
    }
}