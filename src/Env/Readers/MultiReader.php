<?php

namespace Skay1994\MyFramework\Env\Readers;

final class MultiReader implements ReaderInterface
{
    /**
     * @var array|ReaderInterface[]
     */
    private array $readers = [
        FileReader::class,
        ServerConstReader::class,
    ];

    /**
     * Retrieves the value associated with the given key from the readers.
     *
     * @param string $key The key to retrieve the value for.
     * @return string|null The value associated with the key, or null if not found.
     */
    public function get(string $key): string|null
    {
        foreach ($this->readers as $reader) {
            $reader = $reader::construct();

            if ($reader->isSupported()) {
                if(!$reader->has($key)) {
                    continue;
                }

                $value = $reader->get($key);

                if ($value === false) {
                    return 'false';
                }

                if ($value === true) {
                    return 'true';
                }

                return (string) $value;
            }
        }

        return null;
    }

    public function has(string $key): bool
    {
        foreach ($this->readers as $reader) {
            $reader = $reader::construct();

            if ($reader->isSupported() && $reader->has($key)) {
                return true;
            }
        }

        return false;
    }

    public static function construct(): ReaderInterface
    {
        return new self();
    }

    public function isSupported(): bool
    {
        return true;
    }
}