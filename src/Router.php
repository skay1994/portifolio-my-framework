<?php

namespace Skay1994\MyFramework;

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

    public function create(string $httpMethod, string $path, string $controller, string $method): self
    {
        $this->collection->put($httpMethod, $path, $controller, $method);
        return $this;
    }

    public function handle(string $uri, string $method = 'GET'): mixed
    {
        $route = $this->collection->findRoute($uri, $method);

        $controller = Container::get($route['use']);

        $args = $this->parseMethodParameters($route);

        return call_user_func_array([$controller, $route['handle']], $args);
    }

    public function registerRouters(): void
    {
        $files = $this->findClassInFolder(App::controllersPath());

        foreach ($files as $file) {
            try {
                $this->buildRoutes($file);
            } catch (\Exception $exception) {
                $msg = sprintf("Failed to register routes for class [%s]: %s", $file, $exception->getMessage());
                throw new \RuntimeException($msg);
            }
        }
    }

    public function clearRoutes(): void
    {
        $this->collection->clearRoutes();
    }
}