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
     * Retrieves all the routed methods from a ReflectionClass that have the Route attribute and are public.
     *
     * @param ReflectionClass $reflection The reflection class to retrieve the methods from.
     * @return array<int, ReflectionMethod> An array of routed methods.
     */
    public function getRoutedMethods(ReflectionClass $reflection): array
    {
        $methods = [];

        foreach ($reflection->getMethods() as $method) {
            if ($method->getAttributes(Route::class) && $method->isPublic()) {
                $methods[] = $method;
            }
        }

        return $methods;
    }
}