<?php

namespace Skay1994\MyFramework\Facades;

use Skay1994\MyFramework\Facade;

/**
 * @method static mixed get(string $key, mixed $default)
 * @method static bool has(string $key)
 */
class Env extends Facade
{
    protected static function name(): string
    {
        return 'env';
    }
}