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

    public function registerRouters(): array
    {
        $routers = [];

        $files = $this->findClassInFolder(App::controllersPath());

        return $routers;
    }

}