<?php

namespace Skay1994\MyFramework\Facades;

use Skay1994\MyFramework\Facade;
use Skay1994\MyFramework\Filesystem\File;

/**
 * @method static get(string $key = null, mixed $default = null)
 * @method static set(string $key, mixed $value, bool $overwrite = true)
 * @method static load(string|File $file, string $type = 'app', bool $fullPath = false)
 * @method static void reset()
 *
 * @see \Skay1994\MyFramework\Config
 */
class Config extends Facade
{
    protected static function name(): string
    {
        return 'config';
    }
}