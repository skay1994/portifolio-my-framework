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

/**
 * Retrieves a value from a nested array or object using dot notation.
 *
 * @link https://github.com/laravel/framework/blob/11.x/src/Illuminate/Collections/helpers.php#L46
 *
 * @param mixed $target The target array or object to retrieve the value from.
 * @param array|string $key The key or keys to use for retrieval. If an array, each key will be used in order.
 * @param mixed|null $default The default value to return if the key is not found.
 * @return mixed The retrieved value, or the default value if the key is not found.
 */
function data_get(mixed $target, array|string $key, mixed $default = null): mixed
{
    $keys = is_array($key) ? $key : explode('.', $key);

    foreach ($keys as $i => $segment) {
        unset($keys[$i]);

        if (is_null($segment)) {
            return $target;
        }

        if ($segment === '*') {
            if (!is_iterable($target)) {
                return value($default);
            }

            $result = [];

            foreach ($target as $item) {
                $result[] = data_get($item, $key);
            }

            return $result;
        }

        $segment = match ($segment) {
            '\*' => '*',
            '{first}' => array_key_first(is_array($target) ? $target : [$target]),
            '{last}' => array_key_last(is_array($target) ? $target : [$target]),
            default => $segment,
        };

        if(is_array($target) && array_key_exists($segment, $target)) {
            $target = $target[$segment];
        } elseif (is_object($target) && isset($target->{$segment})) {
            $target = $target->{$segment};
        } else {
            return value($default);
        }
    }

    return $target;
}

/**
 * Sets a value in a nested array or object using dot notation.
 *
 * @link https://github.com/laravel/framework/blob/11.x/src/Illuminate/Collections/helpers.php#L109
 *
 * @param mixed &$target The target array or object to set the value in.
 * @param mixed $key The key or keys to use for setting the value. If an array, each key will be used in order.
 * @param mixed $value The value to set.
 * @param bool $overwrite Whether to overwrite existing values. Defaults to true.
 * @return mixed The modified target array or object.
 */
function data_set(mixed &$target, mixed $key, mixed $value, bool $overwrite = true): mixed
{
    $keys = is_array($key) ? $key : explode('.', $key);
    $segment = array_shift($keys);

    if($segment === '*') {
        if (! Arr::accessible($target)) {
            $target = [];
        }

        if ($keys) {
            foreach ($target as &$inner) {
                data_set($inner, $keys, $value, $overwrite);
            }
        } elseif ($overwrite) {
            foreach ($target as &$inner) {
                $inner = $value;
            }
        }
    } elseif(Arr::accessible($target)) {
        if($keys) {
            if (! Arr::exists($target, $segment)) {
                $target[$segment] = [];
            }

            data_set($target[$segment], $keys, $value, $overwrite);
        } elseif($overwrite || ! Arr::exists($target, $segment)) {
            $target[$segment] = $value;
        }
    } elseif (is_object($target)) {
        if ($keys) {
            if (! isset($target->{$segment})) {
                $target->{$segment} = [];
            }

            data_set($target->{$segment}, $keys, $value, $overwrite);
        } elseif ($overwrite || ! isset($target->{$segment})) {
            $target->{$segment} = $value;
        }
    } else {
        $target = [];

        if ($keys) {
            data_set($target[$segment], $keys, $value, $overwrite);
        } elseif ($overwrite) {
            $target[$segment] = $value;
        }
    }

    return $target;
}