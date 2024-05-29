<?php

namespace Skay1994\MyFramework;

use Skay1994\MyFramework\Enums\ConfigSourceEnum;
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
     * Clears the configuration loaded.
     *
     * @return void
     */
    public function reset(): void
    {
        self::$CONFIG = [];
    }

    public function get(string $key = null, mixed $default = null)
    {
        if(is_null($key)) {
            return self::$CONFIG;
        }

        return data_get(self::$CONFIG, $key, $default);
    }

    public function set(mixed $key, mixed $value = null, bool $overwrite = true): void
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $k => $v) {
            data_set(self::$CONFIG, $k, $v, $overwrite);
        }
    }

    /**
     * Initializes the configuration by resetting the configuration array and loading
     * configuration files from the default and application configuration paths.
     *
     * @return void
     * @throws FileNotFoundException
     * @throws FileFolderNotFoundException
     */
    public function init(): void
    {
        $this->reset();

        $paths = [
            $this->app->defaultConfigPath(),
            $this->app->configPath(),
        ];

        foreach ($paths as $path) {
            if(!$this->filesystem->exists($path)) {
                continue;
            }

            $files = $this->filesystem->files($path);

            foreach ($files as $file) {
                $this->load($file);
            }
        }
    }

    /**
     * Loads a configuration file and merges it with the existing configuration.
     *
     * @param string|File $file The path to the configuration file or a File object.
     * @param ConfigSourceEnum $type The configuration file in folder ('app' or 'framework'). Default is 'app'.
     * @return void
     *
     * @throws FileNotFoundException
     */
    public function load(string|File $file, ConfigSourceEnum $type = ConfigSourceEnum::APP): void
    {
        if(is_string($file)) {
            $file = new File($type->path($file));
        }

        if(!$file->exists) {
            throw new FileNotFoundException('Config file not found: ' . $type->path($file->name_ext));
        }

        $config = $this->getConfigFile($file->path);

        self::$CONFIG[$file->name] = array_merge(self::$CONFIG[$file->name] ?? [], $config);
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

    public function __destruct()
    {
        $this->reset();
    }
}