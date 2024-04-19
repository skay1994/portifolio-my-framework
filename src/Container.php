<?php

namespace Skay1994\MyFramework;

use Skay1994\MyFramework\Container\Exceptions\ClassNotFound;

class Container
{
    public static ?Container $instance = null;

    protected array $instances = [];

    public static function getInstance(): static
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    public function flushAll(): void
    {
        $this->instances = [];
    }

    /**
     * @throws ClassNotFound
     */
    public function resolve(string $class): mixed
    {
        $instance = static::getInstance();

        if(!class_exists($class)) {
            throw new ClassNotFound($class);
        }

        if($classInstance = self::get($class)) {
            return $classInstance;
        }

        $newInstance = new $class;
        $instance->instances[$class] = $newInstance;

        return $newInstance;
    }

    /**
     * @throws ClassNotFound
     */
    public static function make(string $class): mixed
    {
        return static::getInstance()->resolve($class);
    }

    public static function get(string $class)
    {
        $instance = static::getInstance();

        return $instance->instances[$class] ?? null;
    }
}