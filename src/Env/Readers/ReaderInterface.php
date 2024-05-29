<?php

namespace Skay1994\MyFramework\Env\Readers;

interface ReaderInterface
{
    public static function construct(): self;

    public function isSupported(): bool;

    public function get(string $key): mixed;

    public function has(string $key): bool;
}