<?php

use Skay1994\MyFramework\Facade;
use Tests\TestClass\DummyClass;

class DummyClassFacade extends Facade {
    protected static function name(): string
    {
        return 'dummyClass';
    }
}

class UnregisterDummyClassFacade extends Facade {
    protected static function name(): string
    {
        return 'unregisterDummyClass';
    }
}

beforeEach(function () {
    Container::resetDefault();
});

it('Register facade and call it', function () {
    Container::bind('dummyClass', DummyClass::class);

    expect(DummyClassFacade::someMethod())
        ->toBe('someMethod');
});

it('Try get access unregistered facade and throw exception', function () {
    UnregisterDummyClassFacade::someMethod();
})->throws(RuntimeException::class);

it('Register facade and call it with parameters', function () {
    Container::bind('dummyClass', DummyClass::class);

    $value = DummyClassFacade::methodWithParameter('John Doe');

    expect($value)
        ->toBe('Hello, John Doe');
});
