<?php

namespace Tests\TestClass;

class DummyClass {
    public function someMethod(): string
    {
        return 'someMethod';
    }

    public function methodWithParameter(string $name): string
    {
        return 'Hello, ' . $name;
    }
}