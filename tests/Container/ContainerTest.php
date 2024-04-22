<?php

use Skay1994\MyFramework\Container;

class DummyClass {}

class DummyClassParameters {
    public function __construct(
        public DummyFacClass $dummyClass,
        public ?string       $name = null
    )
    {}
}

class DummyClassParameterWithRequired {
    public function __construct(
        public DummyFacClass $dummyClass,
        public string        $name,
        public int           $age,
        public float         $balance,
        public bool          $isActive,
        public array         $array,
        public object        $object
    )
    {}
}

class DummyClassParameterWithDefaultValue {
    public function __construct(
        public DummyFacClass $dummyClass,
        public float         $balance,
        public bool          $isActive = true,
        public ?string       $name = 'John Doe',
        public ?int          $age = 18,
    )
    {}
}

class DummyClassParametersExtra {
    public function __construct(
        public DummyFacClass        $dummyClass,
        public DummyClassParameters $dummyParameters,
    )
    {}
}


beforeEach(function () {
    Container::getInstance()->flushAll();
});

it('Container a singleton instance', function () {
    $instance1 = Container::getInstance();
    $instance2 = Container::getInstance();

    expect($instance1)->toBe($instance2);
});

it('Container can find and construct class', function () {
    $reflection = Container::getInstance()->make(DummyFacClass::class);

    expect($reflection)->toBeInstanceOf(DummyFacClass::class);
});

it('Container don\'t duplicate class instance on use make twice ', function () {
    $reflection = Container::getInstance()->make(DummyFacClass::class);
    $recoveredInstance = Container::getInstance()->make(DummyFacClass::class);

    expect($reflection)
        ->toBeInstanceOf(get_class($recoveredInstance))
        ->toBe($recoveredInstance);
});

it('Container can recovery class from cache', function () {
    $reflection = Container::getInstance()->make(DummyFacClass::class);
    $recoveredInstance = Container::getInstance()->get(DummyFacClass::class);

    expect($reflection)
        ->toBeInstanceOf(get_class($recoveredInstance))
        ->toBe($recoveredInstance);
});

it('Container throws exception if class not found', function () {
    Container::getInstance()->make('\App\Class\NotFound');
})->throws(
    Container\Exceptions\ClassNotFound::class,
    'Service Container: Class [\App\Class\NotFound] not found'
);


## Class constructor resolver

it('Container resolve class constructors with parameters', function () {
    $container = Container::getInstance()->make(DummyClassParameters::class);

    expect($container)
        ->toBeInstanceOf(DummyClassParameters::class)
        ->and($container->dummyClass)->toBeInstanceOf(DummyFacClass::class)
        ->and($container->name)->toBeNull();
});

it('Container resolve class constructors multiple with parameters', function () {
    $container = Container::getInstance()->make(DummyClassParametersExtra::class);

    expect($container)->toBeInstanceOf(DummyClassParametersExtra::class)
        ->dummyParameters->dummyClass->toBeInstanceOf(DummyFacClass::class)
        ->dummyClass->toBeInstanceOf(DummyFacClass::class)->toBe($container->dummyParameters->dummyClass);
});

it('Container resolve class constructor with non null parameters', function () {
    $container = Container::getInstance()->make(DummyClassParameterWithRequired::class);

    expect($container)
        ->toBeInstanceOf(DummyClassParameterWithRequired::class)
        ->dummyClass->toBeInstanceOf(DummyFacClass::class)
        ->name->toBeString()->toBe('')
        ->age->toBeInt()->toBe(0)
        ->balance->toBeFloat()->toBe(0.0)
        ->isActive->toBeBool()->toBeFalse()
        ->array->toBeArray()->toHaveCount(0)
        ->object->toBeObject()->toBeInstanceOf(stdClass::class);
});

it('Container resolve class constructor with default parameters value', function () {
    $container = Container::getInstance()->make(DummyClassParameterWithDefaultValue::class);

    expect($container)
        ->toBeInstanceOf(DummyClassParameterWithDefaultValue::class)
        ->name->toBe('John Doe')
        ->age->toBe(18)
        ->isActive->toBeTrue();
});

## Container bind tests

it('Container bind class with closure', function () {
    $instance = Container::getInstance();
    $instance->bind('dummyClass', fn () => new DummyFacClass);

    expect($instance->get('dummyClass'))->toBeInstanceOf(DummyFacClass::class);
});

it('Container bind class with string', function () {
    $instance = Container::getInstance();
    $instance->bind('dummyClass', DummyFacClass::class);

    expect($instance->get('dummyClass'))->toBeInstanceOf(DummyFacClass::class);
});