<?php

use Skay1994\MyFramework\Facades\Container;
use Skay1994\MyFramework\View;

/**
 * Joins multiple paths together to create a single path.
 *
 * @param string $basePath The base path to start with.
 * @param string[] $paths The paths to append to the base path.
 * @return string The resulting path after joining all the paths together.
 *
 * @link https://github.com/laravel/framework/blob/11.x/src/Illuminate/Filesystem/functions.php
 */
function joinPaths(string $basePath, ...$paths): string
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

/**
 * Renders a view with the specified data.
 *
 * @param string $view The path to the view file.
 * @param array $data An associative array of data to pass to the view.
 * @return string The rendered view content.
 */
function view(string $view, array $data = []): string
{
    return Container::make(View::class)->render($view, $data);
}