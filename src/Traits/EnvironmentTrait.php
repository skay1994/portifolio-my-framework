<?php

namespace Skay1994\MyFramework\Traits;

trait EnvironmentTrait
{
    /**
     * Determines if the current environment is set to production.
     *
     * @return bool Returns true if the current environment is set to production, false otherwise.
     */
    public function isProduction(): bool
    {
        return in_array(env('APP_ENV'), ['production', 'prod'], true);
    }

    /**
     * Determines if the current environment is in testing mode.
     *
     * @return bool Returns true if the current environment is in testing mode, false otherwise.
     */
    public function isTesting(): bool
    {
        return in_array(env('APP_ENV'), ['testing', 'test'], true);
    }

    /**
     * Determines if the current environment is local or development.
     *
     * @return bool Returns true if the current environment is local or development, false otherwise.
     */
    public function isLocal(): bool
    {
        return in_array(env('APP_ENV'), ['local', 'dev'], true);
    }

    public function env(string $key, mixed $default = null)
    {
        return env($key, $default);
    }
}