<?php

namespace Skay1994\MyFramework;

use RuntimeException;

abstract class Facade
{
    protected static array $instances = [];

    protected static function name(): string
    {
        throw new RuntimeException('Facade name not implemented');
    }

    /**
     * @throws Container\Exceptions\ClassNotFound
     * @throws \ReflectionException
     */
    public static function __callStatic(string $name, array $arguments = []): mixed
    {
        $binding = Container::getInstance()->getBinding(static::name());

        if(!$binding) {
            throw new RuntimeException('Facade not implemented');
        }

        $instance = $binding['concrete'];

        if(!is_object($instance) && !is_string($instance)) {
            throw new RuntimeException('Facade cannot resolve ['.$binding['concrete'].'] to access method [' . $name . ']');
        }

        if(is_string($binding['concrete'])) {
            $instance = Container::getInstance()->get($binding['concrete']);
        }

        if(!method_exists($instance, $name)) {
            throw new RuntimeException('Facade not implemented the method [' . $name . ']');
        }

        return $instance->$name(...$arguments);
    }
}