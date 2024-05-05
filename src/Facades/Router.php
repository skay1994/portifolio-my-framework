<?php

namespace Skay1994\MyFramework\Facades;

use Skay1994\MyFramework\Facade;
use Skay1994\MyFramework\Router as RouterFacade;

/**
 * @method static RouterFacade create(string $method, string $path, string $controller, string $method)
 * @method static mixed handle(string $uri, array $method = 'GET')
 * @method static void clearRoutes()
 * @method static void registerRouters(string $folder = null)
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