<?php

namespace Skay1994\MyFramework;

use Skay1994\MyFramework\Container\Exceptions\ClassNotFound;

/**
 * @template TValue
 */
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
     * @param TValue $abstract
     * @return TValue|null
     * @throws ClassNotFound
     */
    public function resolve($abstract): mixed
    {
        $instance = static::getInstance();

        if(!is_string($abstract)) {
            return $abstract;
        }

        if(!class_exists($abstract)) {
            throw new ClassNotFound($abstract);
        }

        if($classInstance = self::get($abstract)) {
            return $classInstance;
        }

        $newInstance = new $abstract;
        $instance->instances[$abstract] = $newInstance;

        return $newInstance;
    }

    /**
     * @param TValue $abstract
     * @return TValue|null
     * @throws ClassNotFound
     */
    public static function make($abstract): mixed
    {
        return static::getInstance()->resolve($abstract);
    }

    public static function get(string $class)
    {
        $instance = static::getInstance();

        return $instance->instances[$class] ?? null;
    }
}