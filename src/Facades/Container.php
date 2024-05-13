<?php

namespace Skay1994\MyFramework\Facades;

use Skay1994\MyFramework\Facade;

/**
 * @method static \mixed get(string $abstract)
 * @method static \mixed make(string $abstract)
 * @method static \void flushAll()
 * @method static \void resetDefault()
 * @method static \void setInstances(?array $instances = null)
 * @method static \array<int, \ReflectionParameter> getMethodArgs(string $namespace, string $method)
 * @method static \array parserParameters(\ReflectionParameter $parameter )
 * @method static \void bind(string $abstract, $concrete)
 * @method static \void singleton(string $abstract, mixed $concrete)
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