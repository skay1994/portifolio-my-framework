<?php

use Skay1994\MyFramework\Facade;
use Skay1994\MyFramework\Facades\Container;

class DummyFacClass
{
    public function someMethod(): string
    {
        return 'someMethod';
    }

    public function methodWithParameter(string $name): string
    {
        return 'Hello, ' . $name;
    }
}

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
    Container::bind('dummyClass', new DummyFacClass);
    expect(DummyClassFacade::someMethod())
        ->toBe('someMethod');
});

it('Try get access unregistered facade and throw exception', function () {
    UnregisterDummyClassFacade::someMethod();
})->throws(RuntimeException::class);

it('Register facade and call it with parameters', function () {
    Container::bind('dummyClass', new DummyFacClass);

    $value = DummyClassFacade::methodWithParameter('John Doe');

    expect($value)
        ->toBe('Hello, John Doe');
});
