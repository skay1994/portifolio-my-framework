<?php

namespace Skay1994\MyFramework\Container;

use ReflectionClass;
use ReflectionException;
use Skay1994\MyFramework\Container\Exceptions\ClassNotFound;
use Skay1994\MyFramework\Container\Exceptions\ReflectionErrorException;

trait ClassHelperContainer
{
    /**
     * @throws ClassNotFound
     */
    private function classResolve(string $abstract): object|null
    {
        if(!$this->isClass($abstract)) {
            throw new ClassNotFound($abstract);
        }

        try {
            $reflection = new ReflectionClass($abstract);
            $constructor = $reflection->getConstructor();

            if ($constructor === null) {
                return new $abstract;
            }

            $dependencies = [];

            foreach ($constructor->getParameters() as $parameter) {
                $dependencies[] = $this->parserParameters($parameter);
            }

            return $reflection->newInstanceArgs($dependencies);
        } catch (ReflectionException $e) {
            throw new ReflectionErrorException($e->getMessage(), $e->getCode());
        }
    }

    private function parserParameters(\ReflectionParameter $parameter)
    {
        $typeName = $parameter->getType()?->getName();

        if($this->isClass($typeName)) {
            return $this->resolve($typeName);
        }

        if($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        if($parameter->allowsNull()) {
            return null;
        }


        return match ($typeName) {
            'int' => 0,
            'float' => 0.0,
            'bool' => false,
            default => ''
        };
    }

    private function isClass(string $class): bool
    {
        return class_exists($class) || interface_exists($class);
    }
}