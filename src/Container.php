<?php

namespace Skay1994\MyFramework;

use Closure;
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

    /**
     * @var array<string>
     */
    protected array $resolved = [];

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
        $this->resolved = [];
    }

    /**
     * Binds a concrete implementation to an abstract class or interface.
     *
     * @param string $abstract The name of the abstract class or interface.
     * @param mixed|null $concrete The concrete implementation or closure.
     * @param bool $shared Whether the binding should be shared or not.
     * @return void
     * @throws RuntimeException If the concrete parameter is null.
     */
    public function bind(string $abstract, mixed $concrete, bool $shared = false): void
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

        $this->bindings[$abstract] = compact('concrete', 'shared');
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

    /**
     * Check if a given abstract type is resolved.
     *
     * @param string $abstract The abstract type to check
     * @return bool
     */
    public function resolved(string $abstract): bool
    {
        return isset($this->resolved[$abstract]) ||
            isset($this->instances[$abstract]);
    }

    /**
     * Get an instance from the container if it exists, otherwise resolve it.
     *
     * @param string $abstract The abstract class or interface name.
     * @return mixed The resolved instance.
     *
     * @throws ClassNotFound|\ReflectionException
     */
    public function get(string $abstract): mixed
    {
        if(isset($this->instances[$abstract]) && $instance = $this->instances[$abstract]) {
            return $instance;
        }

        if(isset($this->bindings[$abstract]) && $item = $this->bindings[$abstract]) {
            $abstract = $item['concrete'];

            if($abstract instanceof Closure) {
                return $abstract($this);
            }
        }

        return $this->resolve($abstract);
    }

    /**
     * Get the binding for a given abstract.
     *
     * @param string $abstract The abstract to get the binding for.
     * @return mixed
     */
    public function getBinding(string $abstract): mixed
    {
        return $this->bindings[$abstract] ?? null;
    }
}