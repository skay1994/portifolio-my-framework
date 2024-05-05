<?php

namespace Skay1994\MyFramework\Router;

use Skay1994\MyFramework\Exceptions\Route\NotFoundException;

class RouteCollection
{
    protected static array $routes = [];

    /**
     * Clears all routes from the routes array.
     *
     * @return void
     */
    public function clearRoutes(): void
    {
        self::$routes = [];
    }

    /**
     * Retrieves the routes array or a specific method's routes array.
     *
     * @param string|null $method The HTTP method to retrieve routes for. If null, returns all routes.
     * @return array The routes array or the specified method's routes array.
     */
    public function getRoutes(string $method = null): array
    {
        if($method) {
            $method = strtoupper($method);
            return self::$routes[$method] ?? [];
        }

        return self::$routes;
    }

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
     * Adds a controller route to the routes array for each router in the provided array.
     *
     * @param array $controllerRoute The controller route to be added.
     * @param array $routers The array of routers to add the controller route to.
     * @return void
     */
    public function addController(array $controllerRoute, array $routers): void
    {
        foreach ($routers as $router) {
            foreach ($router['methods'] as $method) {
                $this->put($method, $router['path'], $controllerRoute['use'], $router['handle']);
            }
        }
    }

    /**
     * Find a route for the given URI and method.
     *
     * @param string $uri The URI to find the route for.
     * @param string $method The HTTP method for the route.
     * @throws NotFoundException Route not found for the provided URI and method.
     * @return array The found route.
     */
    public function findRoute(string $uri, string $method): array
    {
        $route = $this->findExactRoute($uri, $method);

        if(!$route) {
            throw new NotFoundException('Route not found for URI [' . $uri . '] and method [' . $method . ']');
        }

        return $route;
    }

    /**
     * Find the exact route for the given URI and method.
     *
     * @param string $uri The URI to find the route for.
     * @param string $method The HTTP method for the route.
     * @return mixed The found route or false if not found.
     */
    private function findExactRoute(string $uri, string $method): mixed
    {
        $routes = self::$routes[$method];

        foreach ($routes as $route) {
            if ($route['path'] === $uri || $route['path'] === $uri . '/') {
                return $route;
            }
        }

        return false;
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