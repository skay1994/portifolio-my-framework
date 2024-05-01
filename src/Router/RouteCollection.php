<?php

namespace Skay1994\MyFramework\Router;

class RouteCollection
{
    protected static array $routes = [];

    public function put(string $path, array $methods): void
    {
        self::$routes[] = [
            'path' => $path,
            'methods' => $methods
        ];
    }
}