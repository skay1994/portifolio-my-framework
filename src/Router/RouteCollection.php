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