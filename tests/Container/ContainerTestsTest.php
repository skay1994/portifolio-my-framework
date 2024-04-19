<?php

use Skay1994\MyFramework\Container;

class DummyClass {}

beforeEach(function () {
    Container::getInstance()->flushAll();
});

it('Container a singleton instance', function () {
    $instance1 = Container::getInstance();
    $instance2 = Container::getInstance();

    expect($instance1)->toBe($instance2);
});

it('Container can find and construct class', function () {
    $reflection = Container::make(DummyClass::class);

    expect($reflection)->toBeInstanceOf(DummyClass::class);
});

it('Container don\'t duplicate class instance on use make twice ', function () {
    $reflection = Container::make(DummyClass::class);
    $recoveredInstance = Container::make(DummyClass::class);

    expect($reflection)
        ->toBeInstanceOf(get_class($recoveredInstance))
        ->toBe($recoveredInstance);
});

it('Container can recovery class from cache', function () {
    $reflection = Container::make(DummyClass::class);
    $recoveredInstance = Container::get(DummyClass::class);

    expect($reflection)
        ->toBeInstanceOf(get_class($recoveredInstance))
        ->toBe($recoveredInstance);
});

it('Container throws exception if class not found', function () {
    Container::make('\App\Class\NotFound');
})->throws(
    Container\Exceptions\ClassNotFound::class,
    'Service Container: Class [\App\Class\NotFound] not found'
);
