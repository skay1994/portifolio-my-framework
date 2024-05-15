<?php

namespace Skay1994\MyFramework\Traits;

trait FilesystemHelper
{
    /**
     * Returns the base path of the application.
     *
     * @return string|null The base path of the application.
     */
    public function basePath(): ?string
    {
        return $this->app_path;
    }

    /**
     * Sets the base path of the application.
     *
     * @param string $appPath The base path of the application.
     * @return void
     */
    public function setBasePath(string $appPath): void
    {
        $this->app_path = $appPath;
    }

    /**
     * Returns the path to the controllers directory.
     *
     * @return string The path to the controllers directory.
     */
    public function controllersPath(): string
    {
        return joinPaths($this->app_path, 'src', 'http','controllers');
    }

    /**
     * Returns the path to a resource file or directory.
     *
     * @param string ...$path The path segments to append to the base resource path.
     * @return string The path to the resource file or directory.
     */
    public function resourcePath(): string
    {
        return joinPaths($this->app_path, 'resources', ...func_get_args());
    }

    /**
     * Returns the path to a view file or directory.
     *
     * @param string ...$path The path segments to append to the base view path.
     * @return string The path to the view file or directory.
     */
    public function viewsPath(): string
    {
        return $this->resourcePath('views', ...func_get_args());
    }
}