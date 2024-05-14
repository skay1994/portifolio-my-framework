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
     * Returns the path to the controllers directory.
     *
     * @return string The path to the controllers directory.
     */
    public function controllersPath(): string
    {
        return joinPaths($this->app_path, 'src', 'http','controllers');
    }
}