<?php

namespace Skay1994\MyFramework\Env\Readers;

use Skay1994\MyFramework\Application;
use Skay1994\MyFramework\Facades\Container;

class FileReader implements ReaderInterface
{
    public static function construct(): self
    {
        /** @var Application $app */
        $app = Container::get('app');
        $instance = new self;
        $instance->loadEnv($app->basePath('.env'));

        if($instance->has('APP_ENV')) {
            $file = '.env.'.$instance->get('APP_ENV');
            $instance->loadEnv($app->basePath($file));
        }

        return $instance;
    }

    public function isSupported(): bool
    {
        return \function_exists('getenv') && \function_exists('putenv');
    }

    /**
     * Retrieves the value of the specified environment variable.
     *
     * @param string $key The name of the environment variable.
     * @return mixed The value of the environment variable, or false if it is not set.
     */
    public function get(string $key): mixed
    {
        return getenv($key);
    }

    public function has(string $key): bool
    {
        return $this->get($key) !== false;
    }

    /**
     * Writes the given key-value pair as an environment variable.
     *
     * @param string $key The name of the environment variable.
     * @param string $value The value of the environment variable.
     * @return void
     */
    public function write(string $key, string $value): void
    {
        putenv(sprintf("%s=%s", $key, $value));
    }

    private function loadEnv(string $file = '.env'): void
    {
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            [$name, $value] = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (preg_match('/^"(.*)"$/', $value, $matches) || preg_match("/^'(.*)'$/", $value, $matches)) {
                $value = $matches[1];
            }

            $_ENV[$name] = $value;
            $this->write($name, $value);
        }
    }
}