<?php

namespace Skay1994\MyFramework;

use Skay1994\MyFramework\Exceptions\Filesystem\FileFolderNotFoundException;
use Skay1994\MyFramework\Exceptions\Filesystem\FileNotFoundException;
use Skay1994\MyFramework\Filesystem\File;

class Config
{
    private static array $CONFIG = [];

    public function __construct(
        protected Application $app,
        protected Filesystem $filesystem
    )
    {
    }

    /**
     * @throws FileFolderNotFoundException
     * @throws FileNotFoundException
     */
    public function init(): void
    {
        $emptyAppPath = false;
        $path = $this->app->configPath();

        if(empty($this->app->basePath())) {
            $emptyAppPath = true;
            $path = $this->app->defaultConfigPath();
        }

        $files = $this->filesystem->files($path);

        foreach ($files as $file) {
            $this->load($file);

            if(!$emptyAppPath) {
                $this->load($file->name_ext, type: 'framework');
            }
        }
    }

    /**
     * Loads a configuration file and merges it with the existing configuration.
     *
     * @param string|File $file The path to the configuration file or a File object.
     * @param string $type The type of configuration file ('app' or 'framework'). Default is 'app'.
     * @param bool $fullPath Whether to use the provided file path directly. Default is false.
     * @return void
     *
     * @throws FileNotFoundException
     */
    public function load(string|File $file, string $type = 'app', bool $fullPath = false): void
    {
        if(is_string($file)) {
            $path = match($type) {
                'app' => joinPaths($this->app->configPath(), $file),
                'framework' => joinPaths($this->app->defaultConfigPath(), $file),
            };

            if($fullPath) {
                $path = $file;
            }

            $file = new File($path);
        }

        if(!$file->exists) {
            return;
        }

        $config = $this->getConfigFile($file->path);

        if(isset(self::$CONFIG[$file->name])) {
            $config = array_merge($config, self::$CONFIG[$file->name]);
        }

        self::$CONFIG[$file->name] = $config;
    }

    /**
     * @throws FileNotFoundException
     */
    private function getConfigFile(string $path): array
    {
        if(!$this->filesystem->exists($path)) {
            return [];
        }

        $config = $this->filesystem->getRequired($path);

        if(!is_array($config)) {
            return [];
        }

        return $config;
    }
}