<?php

namespace Skay1994\MyFramework\Env\Readers;

interface ReaderInterface
{
    public function isSupported(): bool;

    public function get(string $key): mixed;
}