<?php

namespace Skay1994\MyFramework;

use Skay1994\MyFramework\Facades\App;
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

    public function create(string $path, array $methods = []): self
    {
        $this->collection->put($path, $methods);
        return $this;
    }

    public function handle(string $uri, string $method = 'GET'): mixed
    {
        //
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

}