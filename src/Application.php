<?php

namespace Skay1994\MyFramework;

use Skay1994\MyFramework\Traits\FilesystemHelper;

class Application
{
    use FilesystemHelper;

    const VERSION = '0.0.1';

    public Container $container;

    public function __construct(
        public ?string $app_path = null
    )
    {
        $this->container = Container::getInstance();
    }

    public function run()
    {
        $this->defaultFacades();
        echo $this->helloWorld();
    }

    private function helloWorld()
    {
        return 'My Framework by Jorge Carlos v'.self::VERSION;
    }

    public function defaultFacades(): void
    {
        $container = $this->container;

        $facades = [
            'container' => $container,
            'app' => $this,
            'router' => Router::class,
        ];

        foreach ($facades as $key => $value) {
            $container->singleton($key, $value);
        }
    }
}