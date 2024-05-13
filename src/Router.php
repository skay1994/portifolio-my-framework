<?php

namespace Skay1994\MyFramework;

use Skay1994\MyFramework\Exceptions\Route\NotFoundException;
use Skay1994\MyFramework\Facades\App;
use Skay1994\MyFramework\Facades\Container;
use Skay1994\MyFramework\Router\ClassHelper;
use Skay1994\MyFramework\Router\FilesystemHelper;
use Skay1994\MyFramework\Router\RouteCollection;

class Router
{
    use ClassHelper;
    use FilesystemHelper;

    public function __construct(
        protected RouteCollection $collection
    )
    {
    }

    /**
     * Handles the request by finding the appropriate route and executing the associated controller method.
     *
     * @param string $uri The URI of the request.
     * @param string $method The HTTP method of the request (default: 'GET').
     * @return mixed The result of the controller method execution.
     * @throws NotFoundException If the route or controller cannot be found.
     */
    public function handle(string $uri, string $method = 'GET'): mixed
    {
        $route = $this->collection->findRoute($uri, $method);

        $controller = Container::get($route->getController());

        $args = $this->parseMethodParameters($route);

        return call_user_func_array([$controller, $route->getHandle()], $args);
    }

    /**
     * Registers the routers in the specified folder or the default controllers path.
     *
     * @param string|null $folder The folder to search for router classes. If null, the default controllers path is used.
     * @throws \RuntimeException If there is an error registering the routes for a class.
     * @return void
     */
    public function registerRouters(string $folder = null): void
    {
        if (is_null($folder)) {
            $folder = App::controllersPath();
        }

        $files = $this->findClassInFolder($folder);

        foreach ($files as $file) {
            try {
                $this->buildRoutes($file);
            } catch (\Exception $exception) {
                $msg = sprintf("Failed to register routes for class [%s]: %s", $file, $exception->getMessage());
                throw new \RuntimeException($msg);
            }
        }
    }

    /**
     * Clears all routes from the routes array.
     *
     * @return void
     */
    public function clearRoutes(): void
    {
        $this->collection->clearRoutes();
    }

    /**
     * Retrieves the routes array or a specific method's routes array.
     *
     * @param string|null $method The HTTP method to retrieve routes for. If null, returns all routes.
     * @return array The routes array or the specified method's routes array.
     */
    public function getRouters(string $method = null): array
    {
        return $this->collection->getRoutes($method);
    }
}