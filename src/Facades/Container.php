<?php

namespace Skay1994\MyFramework\Facades;

use Skay1994\MyFramework\Facade;

/**
 * @method static get(string $abstract): mixed
 * @method static make(string $abstract): mixed
 * @method static flushAll(): void
 * @method static resetDefault(): void
 * @method static setInstances(?array $instances = null): void
 * @method static bind(string $abstract, $concrete): void
 * @method static singleton(string $abstract, mixed $concrete): void
 *
 * @see \Skay1994\MyFramework\Container
 */
class Container extends Facade
{
    protected static function name(): string
    {
        return 'container';
    }
}