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

        $instance = Container::getInstance()->get($binding['concrete']);

        if(!method_exists($instance, $name)) {
            throw new RuntimeException('Facade not implemented the method [' . $name . ']');
        }

        return $instance->$name(...$arguments);
    }
}