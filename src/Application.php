<?php

namespace Skay1994\MyFramework;

use Skay1994\MyFramework\Traits\FilesystemHelper;
use Spatie\Ignition\Ignition;

class Application
{
    use FilesystemHelper;

    const VERSION = '0.0.1';

    public Container $container;

    public function __construct(
        public ?string $app_path = ''
    )
    {
        $this->container = Container::getInstance();
    }

    public function run()
    {
        if (class_exists('Spatie\Ignition\Ignition')) {
            Ignition::make()->setTheme('dark')->register();
        }

        $this->defaultFacades();

        $this->container->get(Config::class)->init();
    }

    /**
     * Initializes the default facades for the application.
     *
     * This function sets up the default facades for the application by registering them as singletons in the container.
     *
     * @return void
     */
    public function defaultFacades(): void
    {
        $container = $this->container;
        $container->singleton('app', $this);
        $container->singleton('container', $container);

        $facades = [
            'router' => $container->get(Router::class),
            'config' => $container->get(Config::class),
        ];

        foreach ($facades as $key => $value) {
            $container->singleton($key, $value);
        }
    }

    /**
     * Discovers and registers routes for the application.
     *
     * @return void
     */
    public function routeDiscovery(): void
    {
        /** @var Router $router */
        $router = $this->container->get('router');
        $router->registerRouters();
    }
}