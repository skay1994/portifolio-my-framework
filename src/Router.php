<?php

namespace Skay1994\MyFramework;

use Skay1994\MyFramework\Router\RouteCollection;

class Router
{
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
}