<?php

namespace Skay1994\MyFramework\Router;

class Route
{
    private ?string $requestURI = null;

    private string $path;

    private string $controller;

    private string $handle;

    public function __construct(
        protected string $httpMethod,
        protected array $details
    )
    {
        $this->path = $this->details['path'];
        $this->controller = $this->details['use'];
        $this->handle = $this->details['handle'];
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getHandle(): string
    {
        return $this->handle;
    }

    public function match(string $uri): bool
    {
        $this->requestURI = '/'.ltrim($uri, '/');

        if($this->exactRoute()) {
            return true;
        }

        return false;
    }

    private function exactRoute(): bool
    {
        $routePath = $this->path;
        $uri = $this->requestURI;

        $pathWithoutSlashers = ltrim($routePath, '/');
        $pathWithoutSlashers = rtrim($pathWithoutSlashers, '/');

        $uriWithoutSlashers = ltrim($uri, '/');
        $uriWithoutSlashers = rtrim($uriWithoutSlashers, '/');

        return $pathWithoutSlashers === $uriWithoutSlashers;
    }
}