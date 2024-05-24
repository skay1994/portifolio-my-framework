<?php

namespace Skay1994\MyFramework;

use Skay1994\MyFramework\Exceptions\Filesystem\FileNotFoundException;

class Config
{
    public function __construct(
        protected Application $app,
        protected Filesystem $filesystem
    )
    {
    }

    /**
     * @throws FileNotFoundException
     */
    private function getAppConfigFile(string $file): array
    {
        $path = joinPaths($this->app->basePath(), 'config', $file);

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