<?php

use Skay1994\MyFramework\Container;

class DummyClass {}

class DummyClassParameters {
    public function __construct(
        public DummyClass $dummyClass,
        public ?string       $name = null
    )
    {}
}

class DummyClassParameterWithRequired {
    public function __construct(
        public DummyClass $dummyClass,
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
        public DummyClass $dummyClass,
        public float         $balance,
        public bool          $isActive = true,
        public ?string       $name = 'John Doe',
        public ?int          $age = 18,
    )
    {}
}

class DummyClassParametersExtra {
    public function __construct(
        public DummyClass        $dummyClass,
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
    $reflection = Container::getInstance()->make(DummyClass::class);

    expect($reflection)->toBeInstanceOf(DummyClass::class);
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
        ->and($container->dummyClass)->toBeInstanceOf(DummyClass::class)
        ->and($container->name)->toBeNull();
});

it('Container resolve class constructors multiple with parameters', function () {
    $container = Container::getInstance()->make(DummyClassParametersExtra::class);

    expect($container)->toBeInstanceOf(DummyClassParametersExtra::class)
        ->dummyParameters->dummyClass->toBeInstanceOf(DummyClass::class)
        ->dummyClass->toBeInstanceOf(DummyClass::class)->toBe($container->dummyParameters->dummyClass);
});

it('Container resolve class constructor with non null parameters', function () {
    $container = Container::getInstance()->make(DummyClassParameterWithRequired::class);

    expect($container)
        ->toBeInstanceOf(DummyClassParameterWithRequired::class)
        ->dummyClass->toBeInstanceOf(DummyClass::class)
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
    $instance->bind('dummyClass', fn () => new DummyClass);

    expect($instance->get('dummyClass'))->toBeInstanceOf(DummyClass::class);
});

it('Container bind class with string', function () {
    $instance = Container::getInstance();
    $instance->bind('dummyClass', DummyClass::class);

    expect($instance->get('dummyClass'))->toBeInstanceOf(DummyClass::class);
});