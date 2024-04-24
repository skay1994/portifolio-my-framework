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
    $this->container->resetDefault();
});

it('Register facade and call it', function () {
    $this->container->bind('dummyClass', DummyClass::class);

    expect(DummyClassFacade::someMethod())
        ->toBe('someMethod');
});

it('Try get access unregistered facade and throw exception', function () {
    UnregisterDummyClassFacade::someMethod();
})->throws(RuntimeException::class);

it('Register facade and call it with parameters', function () {
    $this->container->bind('dummyClass', DummyClass::class);

    expect(DummyClassFacade::methodWithParameter('John Doe'))
        ->toBe('Hello, John Doe');
});
