<?php

namespace Skay1994\MyFramework\Router;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Skay1994\MyFramework\Attributes\Route;

trait ClassHelper
{
    /**
     * Checks if the given namespace has any route attributes.
     *
     * @param string $namespace The namespace to check.
     * @return bool True if the namespace has any route attributes, false otherwise.
     */
    public function hasAnyRouteAttribute(string $namespace): bool
    {
        try {
            $reflection = new ReflectionClass($namespace);

            $classAttribute = $reflection->getAttributes(Route::class);
            $methodAttribute = false;

            foreach ($reflection->getMethods() as $method) {
                if ($method->getAttributes(Route::class)) {
                    $methodAttribute = true;
                    break;
                }
            }

            if(!$methodAttribute && count($classAttribute) > 0) {
                return false;
            }

            return count($classAttribute) > 0 || $methodAttribute;
        } catch (ReflectionException) {
            return false;
        }
    }

    /**
     * Retrieves an array of ReflectionMethod objects representing the methods in the given namespace that have the Route attribute.
     *
     * @param string $namespace The fully qualified namespace of the class to retrieve the methods from.
     * @return array<int, ReflectionMethod> An array of ReflectionMethod objects representing the methods with the Route attribute, or an array containing a single false value if an exception is caught.
     */
    public function getRoutedMethods(string $namespace): array
    {
        try {
            $reflection = new ReflectionClass($namespace);
            $methods = [];

            foreach ($reflection->getMethods() as $method) {
                if ($method->getAttributes(Route::class)) {
                    $methods[] = $method;
                    break;
                }
            }

            return $methods;
        } catch (ReflectionException) {
            return [];
        }
    }
}