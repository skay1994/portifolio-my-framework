<?php

namespace Skay1994\MyFramework\Filesystem;

/**
 * @property-read string $path
 * @property-read string $name
 * @property-read string $name_ext
 * @property-read string $mimetype
 * @property-read int $size
 * @property-read mixed $content
 * @property-read bool $exists
 */
readonly class File
{
    public function __construct(
        private string $path
    )
    {
    }

    /**
     * Returns the name of the file without the extension.
     *
     * @return string The name of the file.
     */
    public function name(): string
    {
        return basename($this->path, '.' . pathinfo($this->path, PATHINFO_EXTENSION));
    }

    /**
     * Returns the name of the file with the extension.
     *
     * @return string The name of the file with the extension.
     */
    public function nameWithExtension(): string
    {
        return basename($this->path);
    }

    public function path(): string
    {
        return $this->path;
    }

    public function mimetype(): false|string
    {
        return mime_content_type($this->path);
    }

    public function size(): false|int
    {
        return filesize($this->path);
    }

    public function delete(): void
    {
        unlink($this->path);
    }

    public function read(): false|string
    {
        return file_get_contents($this->path);
    }

    public function exists(): bool
    {
        return file_exists($this->path);
    }

    public function __get(string $name)
    {
        return match ($name) {
            'path' => $this->path,
            'name' => $this->name(),
            'name_ext' => $this->nameWithExtension(),
            'mimetype' => $this->mimetype(),
            'size' => $this->size(),
            'content' => $this->read(),
            'exists' => $this->exists(),
            default => null
        };
    }
}