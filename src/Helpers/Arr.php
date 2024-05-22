<?php

namespace Skay1994\MyFramework\Helpers;

class Arr
{
    /**
     * Returns the last element of the given array.
     *
     * @param array $array The input array.
     * @return mixed The last element of the array.
     */
    public static function last(array $array): mixed
    {
        return end($array);
    }

    /**
     * Returns the first element of the given array.
     *
     * @param array $array The input array.
     * @return mixed The first element of the array.
     */
    public static function first(array $array): mixed
    {
        return reset($array);
    }

    /**
     * Returns an array containing only the elements from the given array that have keys specified in the $keys array.
     *
     * @param array $array The input array.
     * @param array $keys The array of keys to filter the input array by.
     * @return array The filtered array containing only the elements with the specified keys.
     */
    public static function only(array $array, array $keys): array
    {
        return array_intersect_key($array, array_flip($keys));
    }

    /**
     * Returns an array containing all the elements from the given array except for the ones with keys specified in the $keys array.
     *
     * @param array $array The input array.
     * @param array|string $keys The array of keys to exclude from the input array. Can be an array of keys or a single key.
     * @return array The filtered array containing all the elements from the input array except for the ones with the specified keys.
     */
    public static function except(array $array, array|string $keys): array
    {
        $result = [];
        $keys = is_array($keys) ? $keys : [$keys];

        foreach ($array as $key => $value) {
            if (!in_array($key, $keys, true)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * Checks if a key exists in the given array.
     *
     * @param array $array The array to check for the key.
     * @param mixed $key The key to check for existence.
     * @return bool Returns true if the key exists in the array, false otherwise.
     */
    public static function exists(array $array, $key): bool
    {
        return array_key_exists($key, $array);
    }

    /**
     * Returns an array containing all the values from the given array.
     *
     * @param array $array The input array.
     * @return array An array containing all the values from the input array.
     */
    public static function values(array $array): array
    {
        return array_values($array);
    }

    /**
     * Returns an array containing all the keys from the given array.
     *
     * @param array $array The input array.
     * @return array An array containing all the keys from the input array.
     */
    public static function keys(array $array): array
    {
        return array_keys($array);
    }

    /**
     * Checks if a value is accessible as an array or implements the ArrayAccess interface.
     *
     * @param mixed $value The value to check for accessibility.
     * @return bool Returns true if the value is accessible, false otherwise.
     */
    public static function accessible(mixed $value): bool
    {
        return is_array($value) || $value instanceof \ArrayAccess;
    }
}