<?php

namespace Skay1994\MyFramework\Facades;

use Skay1994\MyFramework\Facade;
use Skay1994\MyFramework\Router as RouterFacade;

/**
 * @method static RouterFacade create(string $method, string $path, string $controller, string $method)
 * @method static \mixed handle(string $uri, array $method = 'GET')
 * @method static \void clearRoutes(): void
 * @method static \string registerRouters(string $folder = null)
 * @method static \void setNamespaceReplacer(array $replacer = null)
 * @method static \array getNamespaceReplacer()
 * @method static array getRouters(string $method = null)
 *
 * @mixin RouterFacade
 */
class Router extends Facade
{
    protected static function name(): string
    {
        return 'router';
    }
}