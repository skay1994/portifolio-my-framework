<?php

namespace Skay1994\MyFramework\Router;

use Skay1994\MyFramework\Facades\App;

trait FilesystemHelper
{
    private function findClassInFolder(string $path): array
    {
        /** @var \SplFileInfo $file */

        $routers = [];

        $recursiveDirectory = new \RecursiveDirectoryIterator($path);
        $iterator = new \RecursiveIteratorIterator($recursiveDirectory);

        foreach ($iterator as $file) {
            if($file->isDir()) {
                continue;
            }
            if($file->isFile() && $file->getExtension() !== 'php') {
                continue;
            }

            $namespace = $this->getNamespace($file->getPathname());

            if(!class_exists($namespace)) {
                continue;
            }
        }
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