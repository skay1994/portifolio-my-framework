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
        return $this->joinPaths($this->app_path, 'src', 'http','controllers');
    }

    /**
     * Joins multiple paths together to create a single path.
     *
     * @param string $basePath The base path to start with.
     * @param string[] $paths The paths to append to the base path.
     * @return string The resulting path after joining all the paths together.
     *
     * @link https://github.com/laravel/framework/blob/11.x/src/Illuminate/Filesystem/functions.php
     */
    public function joinPaths(string $basePath, ...$paths): string
    {
        foreach ($paths as $index => $path) {
            if (empty($path)) {
                unset($paths[$index]);
            } else {
                $paths[$index] = DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR);
            }
        }

        return $basePath.implode('', $paths);
    }
}