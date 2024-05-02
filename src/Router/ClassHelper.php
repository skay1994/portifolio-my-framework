<?php

namespace Skay1994\MyFramework\Router;

use ReflectionClass;
use ReflectionException;
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

            return count($classAttribute) > 0 || $methodAttribute;
        } catch (ReflectionException) {
            return false;
        }
    }

}