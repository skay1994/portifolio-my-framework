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

    /**
     * @var array<TValue, TValue|null>
     */
    protected array $bindings = [];

    /**
     * @var array<TValue, TValue|null>
     */
    protected array $instances = [];

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
     * @throws RuntimeException If the concrete parameter is object without shared.
     */
    public function bind(string $abstract, mixed $concrete = null, bool $shared = false): void
    {
        if(is_null($concrete)) {
            throw new RuntimeException("Container binding concrete cannot be null");
        }

        if(is_string($concrete) && !$this->isClass($concrete)) {
            throw new RuntimeException("Container binding class {$concrete} not found");
        }

        if(!$concrete instanceof Closure && is_object($concrete) &&  !$shared) {
            throw new RuntimeException("Container can binding an object only on singleton");
        }

        $this->bindings[$abstract] = compact('concrete', 'shared');

        if($shared) {
            if($concrete instanceof Closure) {
                $this->instances[$abstract] = $concrete($this);
                $this->resolved[$abstract] = true;
                return;
            }

            if(is_object($concrete)) {
                $this->instances[$abstract] = $concrete;
                $this->resolved[$abstract] = true;
                return;
            }

            $instance = $this->resolve($concrete);

            if(is_object($instance)) {
                $this->instances[$abstract] = $instance;
                $this->resolved[$abstract] = true;
            }
        }
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