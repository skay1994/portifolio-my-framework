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
     * @param string $handle The method to be called on the controller for the route.
     * @param array $controller The controller to be used for the route.
     * @return void
     */
    public function put(string $method, string $path, string $handle, array $controller): void
    {
        $method = strtoupper($method);

        self::$routes[$method][] = [
            'path' => $path,
            'use' => $controller['use'],
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
                $this->put($method, $router['path'], $router['handle'], $controllerRoute);
            }
        }
    }

    /**
     * Find a route for the given URI and method.
     *
     * @param string $uri The URI to find the route for.
     * @param string $method The HTTP method for the route.
     * @throws NotFoundException Route not found for the provided URI and method.
     * @return Route The found route.
     */
    public function findRoute(string $uri, string $method): Route
    {
        $method = strtoupper($method);

        $route = array_filter($this->getRoutes($method) ?? [], static fn(Route $route) => $route->match($uri));
        $route = current($route);

        if(!$route) {
            throw new NotFoundException('Route not found for URI [' . $uri . '] and method [' . $method . ']');
        }

        return $route;
    }
}