<?php

namespace Skay1994\MyFramework\Facades;

use Skay1994\MyFramework\Application;
use Skay1994\MyFramework\Facade;

/**
 * @method static run()
 *
 * @method static basePath()
 * @method static controllersPath()
 * @method static joinPaths()
 *
 * @see Application
 */
class App extends Facade
{
    protected static function name(): string
    {
        return 'app';
    }
}