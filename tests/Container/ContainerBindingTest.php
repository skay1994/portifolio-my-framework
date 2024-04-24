<?php

use Tests\TestClass\DummyClass;
use Tests\TestClass\DummyClassWithInterface;
use Tests\TestClass\DummyInterface;

it('Try register bind with a class instance and throw exception', function () {
    $this->container->bind('dummyClass', new DummyClass);
})->throws(RuntimeException::class, 'Container can binding an object only on singleton');

it('Container bind class with closure', function () {
    $this->container->bind('dummyClass', fn () => new DummyClass);

    expect($this->container->get('dummyClass'))->toBeInstanceOf(DummyClass::class);
});

it('Container bind class with string', function () {
    $this->container->bind('dummyClass', DummyClass::class);

    expect($this->container->get('dummyClass'))->toBeInstanceOf(DummyClass::class);
});

it('Container bind class with interface', function () {
    $this->container->bind(DummyInterface::class, DummyClassWithInterface::class);

    expect($this->container->get(DummyInterface::class))->toBeInstanceOf(DummyClassWithInterface::class);
});