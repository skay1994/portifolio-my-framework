<?php

namespace Skay1994\MyFramework\Router;

use Skay1994\MyFramework\Facades\App;

trait FilesystemHelper
{
    /**
     * Finds all the classes in the given folder and its subfolders.
     *
     * @param string $path The path to the folder.
     * @return array An array of fully qualified class names.
     */
    private function findClassInFolder(string $path): array
    {
        /** @var \SplFileInfo $file */

        $files = [];
        $subFolderFiles = [];

        $recursiveDirectory = new \RecursiveDirectoryIterator($path);

        foreach ($recursiveDirectory as $file) {
            if($file->isDir()) {
                if($file->getFilename() === '.' || $file->getFilename() === '..') {
                    continue;
                }

                $subFolderFiles[] = $this->findClassInFolder($file->getPathname());
                continue;
            }

            if($file->isFile() && $file->getExtension() !== 'php') {
                continue;
            }

            $namespace = $this->getNamespace($file->getPathname());

            if(!class_exists($namespace)) {
                continue;
            }

            $files[] = $namespace;
        }

        return array_merge($files, ...$subFolderFiles);
    }

    /**
     * Generates a namespace from a file name.
     *
     * @param string $fileName The name of the file.
     * @return string The generated namespace.
     */
    private function getNamespace(string $fileName): string
    {
        $search = [
            App::basePath(), '\src', '.php', DIRECTORY_SEPARATOR, '/'
        ];
        $replace = ['', '\App', '', '\\', '\\'];
        $namespace = str_replace($search, $replace, $fileName);

        $map = array_map('ucfirst', explode(DIRECTORY_SEPARATOR, $namespace));

        return implode('\\', $map);
    }
}