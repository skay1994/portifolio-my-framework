<?php

namespace Skay1994\MyFramework\Router;

use Skay1994\MyFramework\Facades\App;

trait FilesystemHelper
{
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