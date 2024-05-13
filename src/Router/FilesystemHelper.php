<?php

namespace Skay1994\MyFramework\Router;

use Skay1994\MyFramework\Facades\App;

trait FilesystemHelper
{
    private ?array $namespaceReplacer = null;

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

            if(!$this->hasAnyRouteAttribute($namespace)) {
                continue;
            }

            $files[] = $namespace;
        }

        return array_merge($files, ...$subFolderFiles);
    }

    /**
     * @var $replacer array{search: array, replace: array}
     *
     * Sets the namespace replacer array for the class.
     *
     * @param array|null $replacer The replacer array with 'search' and 'replace' keys.
     *                            If null, a default replacer array is used.
     * @return static Returns the current instance of the class.
     */
    public function setNamespaceReplacer(array $replacer = null): static
    {
        if(is_null($replacer)) {
            $replacer = [
                'search' => [
                    App::basePath(), '\src', '.php', DIRECTORY_SEPARATOR, '/'
                ],
                'replace' => ['', '\App', '', '\\', '\\']
            ];
        }

        $this->namespaceReplacer = $replacer;
        return $this;
    }

    /**
     * Retrieves the namespace replacer array for the class.
     *
     * @return array The namespace replacer array.
     */
    public function getNamespaceReplacer(): array
    {
        if(is_null($this->namespaceReplacer)) {
            $this->setNamespaceReplacer();
        }

        return $this->namespaceReplacer;
    }

    /**
     * Generates a namespace from a file name.
     *
     * @param string $fileName The name of the file.
     * @return string The generated namespace.
     */
    private function getNamespace(string $fileName): string
    {
        ['search' => $search, 'replace' => $replace] = $this->getNamespaceReplacer();
        $namespace = str_replace($search, $replace, $fileName);

        $map = array_map('ucfirst', explode(DIRECTORY_SEPARATOR, $namespace));

        return implode('\\', $map);
    }
}