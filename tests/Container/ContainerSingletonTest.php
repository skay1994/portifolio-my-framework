<?php

use Tests\TestClass\{DummyClass, DummyInterface, DummyClassWithInterface};

it('Container create singleton by class with interface', function () {
    $this->container->singleton(DummyInterface::class, DummyClassWithInterface::class);

    expect($this->container->get(DummyInterface::class))->toBeInstanceOf(DummyClassWithInterface::class);
});

it('Container create singleton by closure', function () {
    $classInstance = new DummyClass();

    $this->container->singleton(DummyClass::class, fn () => $classInstance);

    expect($this->container->get(DummyClass::class))
        ->toBeInstanceOf(DummyClass::class)
        ->toBe($classInstance);
});

it('Container create singleton by instace', function () {
    $classInstance = new DummyClass();

    $this->container->singleton(DummyClass::class, $classInstance);

    expect($this->container->get(DummyClass::class))
        ->toBeInstanceOf(DummyClass::class)
        ->toBe($classInstance);
});
