<?php

namespace Skay1994\MyFramework\Router;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Skay1994\MyFramework\Attributes\Route;
use Skay1994\MyFramework\Facades\Container;

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

    /**
     * Reflects on a given namespace and creates routes based on the attributes of the class and its methods.
     *
     * @param string $namespace The namespace to reflect on.
     * @throws \ReflectionException If an error occurs while reflecting on the class.
     * @return void
     */
    private function buildRoutes(string $namespace): void
    {
        $reflection = new ReflectionClass($namespace);

        $routes = [];

        $controllerRoute = [
            'group' => null,
            'use' => $namespace,
            'prefix' => null,
            'path' => '/',
        ];

        if($attribute = $reflection->getAttributes(Route::class)) {
            $values = $this->parseAttribute(current($attribute));
            unset($values['methods']);

            $controllerRoute = array_merge($controllerRoute, $values);
        }

        $methods = $this->getRoutedMethods($reflection);

        foreach ($methods as $method) {
            $attribute = current($method->getAttributes(Route::class));

            $route = $this->parseAttribute($attribute, $controllerRoute);
            $route['handle'] = $method->getName();

            $routes[] = $route;
        }

        $this->collection->addController($controllerRoute, $routes);
    }

    /**
     * Parses the given attribute and returns an array with the parsed information.
     *
     * @param ReflectionAttribute $attribute The attribute to parse.
     * @param array $baseRoute An optional array with base route information.
     * @return array An array with the parsed information.
     */
    private function parseAttribute(ReflectionAttribute $attribute, array $baseRoute = []): array
    {
        $args = $attribute->getArguments();

        $path = $this->getAttributeArgValue($args, 'path', 0) ?? '/';
        $methods = $this->getAttributeArgValue($args, 'methods', 1) ?? ['get'];
        $group = $this->getAttributeArgValue($args, 'group', 2) ?? '';
        $prefix = $this->getAttributeArgValue($args, 'prefix', 3) ?? '';

        if($baseRoute) {
            $group = $baseRoute['group'] ?? $group;
            $prefix = $baseRoute['prefix'] ?? $prefix;
        }

        $path = $group . $prefix . $path;

        return [
            'group' => $group,
            'prefix' => $prefix,
            'path' => $path,
            'methods' => array_map('strtoupper', $methods),
        ];
    }

    /**
     * Retrieves the value of an attribute argument based on the provided key or index.
     *
     * @param array $args The array of attribute arguments.
     * @param string $key The key of the argument to retrieve.
     * @param int $index The index of the argument to retrieve.
     * @return mixed The value of the attribute argument, or null if it does not exist.
     */
    private function getAttributeArgValue(array $args, string $key, int $index): mixed
    {
        return $args[$key] ?? $args[$index] ?? null;
    }

    /**
     * Parses the method parameters from the given route array.
     *
     * @param array $route The route array containing the 'use' and 'handle' keys.
     * @return array The parsed method parameters.
     */
    private function parseMethodParameters(array $route): array
    {
        $args = Container::getMethodArgs($route['use'], $route['handle']);

        foreach ($args as $key => $value) {
            $args[$key] = Container::parserParameters($value);
        }

        return $args;
    }
}