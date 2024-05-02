<?php

namespace Skay1994\MyFramework;

use Skay1994\MyFramework\Attributes\Route;
use Skay1994\MyFramework\Facades\App;
use Skay1994\MyFramework\Router\FilesystemHelper;
use Skay1994\MyFramework\Router\RouteCollection;

class Router
{
    use FilesystemHelper;

    public function __construct(
        protected RouteCollection $collection
    )
    {
    }

    public function create(string $path, array $methods = []): self
    {
        $this->collection->put($path, $methods);
        return $this;
    }

    public function handle(string $uri, string $method = 'GET'): mixed
    {
        //
    }

    private function registerRouters(): array
    {
        /** @var \SplFileInfo $file */

        $routers = [];

        $controllersPath = App::controllersPath();
        $recursiveDirectory = new \RecursiveDirectoryIterator($controllersPath);
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