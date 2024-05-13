<?php

namespace Skay1994\MyFramework\Router;

class Route
{
    private ?string $requestURI = null;

    private string $path;

    private string $controller;

    private string $handle;

    /**
     * @var array{path: string, use: string, handle: string, group: ?string, prefix: ?string, methods: array} $details
     *
     * Constructs a new instance of the class.
     *
     * @param string $httpMethod The HTTP method for the route.
     * @param array $details An array containing the details of the route.
     * @return void
     */
    public function __construct(
        protected string $httpMethod,
        protected array $details
    )
    {
        $this->path = '/'.ltrim($this->details['path'], '/');
        $this->controller = $this->details['use'];
        $this->handle = $this->details['handle'];
    }

    /**
     * Retrieves the controller associated with this object.
     *
     * @return string The name of the controller.
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * Retrieves the handle of the current object.
     *
     * @return string The handle of the current object.
     */
    public function getHandle(): string
    {
        return $this->handle;
    }

    /**
     * Retrieves the path of the current object.
     *
     * @return string The path of the current object.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Matches the given URI with the route path to determine if they match.
     *
     * @param string $uri The URI to match against the route path.
     * @return bool Returns true if the URI matches the route path, false otherwise.
     */
    public function match(string $uri): bool
    {
        $this->requestURI = '/'.ltrim($uri, '/');

        if($this->exactRoute()) {
            return true;
        }

        if($this->hasParams() && $this->compareUriWithRoute()) {
            return true;
        }

        return false;
    }

    /**
     * Checks if the route path matches the request URI exactly.
     *
     * @return bool
     */
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

    /**
     * Checks if the route path contains parameters.
     *
     * @return bool
     */
    private function hasParams(): bool
    {
        return preg_match('/\{\w+}/', $this->path);
    }

    /**
     * Compares the current request URI with the route path to determine if they match.
     *
     * @return bool Returns true if the URI matches the route path, false otherwise.
     */
    public function compareUriWithRoute(): bool
    {
        $uri = ltrim($this->requestURI, '/');
        $path = ltrim($this->path, '/');
        $path = str_replace('/', '\/', $path);

        $regex = preg_replace('/{[^\/]+}/', '(\w+)', $path);

        return preg_match('/^' . $regex . '$/', $uri);
    }
}