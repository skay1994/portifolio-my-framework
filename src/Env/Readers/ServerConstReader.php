<?php

namespace Skay1994\MyFramework\Env\Readers;

class ServerConstReader implements ReaderInterface
{
    public static function construct(): self
    {
        return new self();
    }

    public function isSupported(): bool
    {
        return true;
    }

    public function get(string $key): mixed
    {
        return $_SERVER[$key] ?? null;
    }

    public function has(string $key): bool
    {
        return isset($_SERVER[$key]);
    }
}