<?php

namespace Skay1994\MyFramework;

use Skay1994\MyFramework\Exceptions\Filesystem\FileFolderNotFoundException;
use Skay1994\MyFramework\Exceptions\Filesystem\FileNotFoundException;

class Filesystem
{
    /**
     * Retrieves the contents of a file based on the provided path.
     *
     * @param string $path The path to the file.
     * @throws FileNotFoundException File not found at path: $path
     * @return false|string The contents of the file if found.
     */
    public function get(string $path): false|string
    {
        if($this->isFile($path)) {
            return file_get_contents($path);
        }

        throw new FileNotFoundException('File not found at path: ' . $path);
    }

    /**
     * Retrieves whether the path is a regular file.
     *
     * @param string $path The path to the file.
     * @return bool
     */
    public function isFile(string $path): bool
    {
        return is_file($path);
    }

    /**
     * Retrieves whether the path is a directory.
     *
     * @param string $path The path to check.
     * @return bool True if the path is a directory, false otherwise.
     */
    public function isDir(string $path): bool
    {
        return is_dir($path);
    }

    /**
     * Retrieves whether the path exists.
     *
     * @param string $path The path to check.
     * @return bool
     */
    public function exists(string $path): bool
    {
        return file_exists($path);
    }

    /**
     * Retrieves whether the path is missing.
     *
     * @param string $path The path to check for missing status.
     * @return bool
     */
    public function missing(string $path): bool
    {
        return !$this->exists($path);
    }

    /**
     * Deletes a file or directory based on the provided path.
     *
     * @param string $path The path to the file or directory.
     * @throws FileFolderNotFoundException File or folder not found to remove: $path
     */
    public function delete(string $path): void
    {
        if($this->isFile($path)) {
            unlink($path);
            return;
        }

        if($this->isDir($path)) {
            rmdir($path);
            return;
        }

        throw new FileFolderNotFoundException('File or folder not found to remove: ' . $path);
    }
}