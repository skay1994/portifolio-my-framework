<?php

namespace Skay1994\MyFramework\Filesystem;

readonly class File
{
    public function __construct(
        private string $path
    )
    {
    }

    public function name(): string
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
}