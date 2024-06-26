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
        return preg_match('/\{\w+}/', $this->path) || preg_match('/\{\?\w+}/', $this->path);
    }

    /**
     * Compares the current request URI with the route path to determine if they match.
     *
     * @return bool Returns true if the URI matches the route path, false otherwise.
     */
    public function compareUriWithRoute(): bool
    {
        $uri = ltrim($this->requestURI, '/');

        return preg_match($this->pathRegex(), $uri);
    }

    /**
     * Retrieves the parameters from the route path based on the current request URI.
     *
     * @return array<string, string> The parameters extracted from the route path.
     */
    public function getParameters(): array
    {
        if(!$this->hasParams()) {
            return [];
        }

        preg_match_all('/\{?(\w+)}/', $this->path, $args);
        preg_match($this->pathRegex(), ltrim($this->requestURI, '/'), $values);
        unset($values[0]);

        if(empty($values)) {
            return [];
        }

        return array_combine($args[1], $values);
    }

    /**
     * Generates a regular expression pattern based on the given path.
     *
     * This function takes the path and performs the following transformations:
     * - Removes the leading slash from the path.
     * - Replaces any slashes with escaped slashes.
     * - Replaces any occurrences of `{parameter}` with `(\w+)`.
     *
     * The resulting regular expression pattern is returned.
     *
     * @return string The generated regular expression pattern.
     */
    public function pathRegex(): string
    {
        $path = ltrim($this->path, '/');
        $path = str_replace('/', '\/', $path);

        $regex = preg_replace('/\{(\w+)}/', '(\w+)', $path);
        $regex = preg_replace('/\{\?(\w+)}/', '?(\w*)', $regex);

        return '/' . $regex . '$/';
    }
}