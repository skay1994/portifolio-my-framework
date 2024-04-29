<?php

namespace Skay1994\MyFramework;

use RuntimeException;
use Skay1994\MyFramework\Container\ClassHelperContainer;
use Skay1994\MyFramework\Container\Exceptions\ClassNotFound;

/**
 * @template TValue
 */
class Container
{
    use ClassHelperContainer;

    public static ?Container $instance = null;

    protected array $bindings = [];

    public static function getInstance(): static
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    public function resetDefault(): void
    {
        $app = $this->get('app');
        $this->flushAll();
        $app->defaultFacades();
    }

    public function flushAll(): void
    {
        $this->instances = [];
        $this->bindings = [];
    }

    public function bind(string $abstract, $concrete): void
    {
        if(is_null($concrete)) {
            throw new RuntimeException("Container binding concrete cannot be null");
        }

        if($concrete instanceof \Closure) {
            $concrete = $concrete($this);
        }

        if(is_string($concrete)) {
            $concrete = $this->make($concrete);
        }

        $this->bindings[$abstract] = $concrete;
    }

    /**
     * @param TValue $abstract
     * @return TValue|null
     * @throws ClassNotFound|\ReflectionException
     */
    private function resolve($abstract): mixed
    {
        $newInstance = null;

        if($classInstance = $this->get($abstract)) {
            return $classInstance;
        }

        if(is_string($abstract)) {
            $newInstance = $this->classResolve($abstract);
        }

        return $newInstance;
    }

    /**
     * @param TValue $abstract
     * @return TValue|null
     * @throws ClassNotFound
     * @throws \ReflectionException
     */
    public function make($abstract): mixed
    {
        return $this->resolve($abstract);
    }

    public function get(string $class)
    {
        if(isset($this->bindings[$class]) && $instance = $this->bindings[$class]) {
            return $instance;
        }

        return null;
    }
}