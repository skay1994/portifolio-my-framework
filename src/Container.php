<?php

namespace Skay1994\MyFramework;

use Skay1994\MyFramework\Container\ClassHelperContainer;
use Skay1994\MyFramework\Container\Exceptions\ClassNotFound;

/**
 * @template TValue
 */
class Container
{
    use ClassHelperContainer;

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
     * @throws ClassNotFound|\ReflectionException
     */
    public function resolve($abstract): mixed
    {
        $newInstance = null;

        if($classInstance = self::get($abstract)) {
            return $classInstance;
        }

        if(is_string($abstract)) {
            $newInstance = $this->classResolve($abstract);
        }

        $this->instances[$abstract] = $newInstance;

        return $newInstance;
    }

    /**
     * @param TValue $abstract
     * @return TValue|null
     * @throws ClassNotFound
     * @throws \ReflectionException
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