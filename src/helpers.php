<?php

use Skay1994\MyFramework\Facades\Container;
use Skay1994\MyFramework\Helpers\Arr;
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

/**
 * Returns the result of calling a Closure with the given arguments if the input is a Closure,
 * otherwise returns the input itself.
 *
 * @param mixed $value The input value to check if it's a Closure.
 * @param mixed ...$args The arguments to pass to the Closure if it is one.
 * @return mixed The result of calling the Closure with the given arguments, or the input value itself.
 */
function value(mixed $value, ...$args): mixed
{
    return $value instanceof Closure ? $value(...$args) : $value;
}
