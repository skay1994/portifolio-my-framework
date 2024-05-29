<?php

namespace Skay1994\MyFramework;

use Skay1994\MyFramework\Env\Readers\MultiReader;

readonly class Env
{
    public function __construct(
        private MultiReader $reader
    )
    {
    }

    /**
     * Retrieves the configuration value for a given key or sets the configuration values.
     *
     * @param string $key The key of the configuration value to retrieve.
     * @param mixed $default The default value to return if the key is not found. Default is null.
     * @return mixed The configuration value for the given key or the default value if the key is not found.
     *               The value is converted to its corresponding PHP type if it is a string representation of a boolean or null.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->reader->get($key);

        if(is_null($value)) {
            return value($default);
        }

        return match (strtolower($value)) {
            'true' => true,
            'false' => false,
            'null' => null,
            default => $value
        };
    }

    public function has(string $key): bool
    {
        return $this->reader->has($key);
    }
}