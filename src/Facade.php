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
        $instance = Container::getInstance()->get(static::name());

        if(!$instance) {
            throw new RuntimeException('Facade not implemented');
        }

        if(!method_exists($instance, $name)) {
            throw new RuntimeException('Facade not implemented the method [' . $name . ']');
        }

        return $instance->$name(...$arguments);
    }
}