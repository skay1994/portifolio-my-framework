<?php

namespace Skay1994\MyFramework\Router;

class RouteCollection
{
    protected static array $routes = [];

    /**
     * Adds a new route to the routes array.
     *
     * @param string $method The HTTP method for the route.
     * @param string $path The path for the route.
     * @param string $use The controller to be used for the route.
     * @param string $handle The method to be called on the controller for the route.
     * @return void
     */
    public function put(string $method, string $path, string $use, string$handle): void
    {
        self::$routes[$method][] = [
            'path' => $path,
            'use' => $use,
            'handle' => $handle
        ];
    }

    /**
     * Parses the given URI and returns an array of its parts.
     *
     * @param string $uri The URI to be parsed.
     * @return array The array of URI parts.
     */
    private function parserURI(string $uri): array
    {
        $uriParts = explode('/', $uri);
        return array_filter($uriParts);
    }
}