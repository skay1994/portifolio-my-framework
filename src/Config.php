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
     * @throws FileNotFoundException
     */
    private function getConfigFile(string $file): array
    {
        $path = joinPaths(dirname(__DIR__), 'src', 'config', $file);

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