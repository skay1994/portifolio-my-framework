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

        $facades = [
            'container' => $container,
            'app' => $this,
            'router' => $container->get(Router::class),
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