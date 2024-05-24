<?php

namespace Skay1994\MyFramework;

use Skay1994\MyFramework\Exceptions\Filesystem\FileFolderNotFoundException;
use Skay1994\MyFramework\Exceptions\Filesystem\FileNotFoundException;

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
        $files = $this->filesystem->files($this->app->configPath());

        foreach ($files as $file) {
            $name = str_replace('.php', '', $file->name());
            $appConfig = $this->filesystem->getRequired($file->path());
            $config = $this->getConfigFile($file->name());

            self::$CONFIG[$name] = array_merge($config, $appConfig);
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