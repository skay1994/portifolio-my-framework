<?php

namespace Skay1994\MyFramework\Router;

use Skay1994\MyFramework\Exceptions\Route\NotFoundException;

class RouteCollection
{
    /**
     * @var array<string, array<int, Route>>
     */
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
     * @return array<string, array<int, Route>>|array<int, Route> The routes array or the specified method's routes array.
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
     * @param Route $route The route object to be added.
     * @return void
     */
    public function put(string $method, Route $route): void
    {
        $method = strtoupper($method);

        self::$routes[$method][] = $route;
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
                $this->put($method, new Route($method, [...$controllerRoute, ...$router]));
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