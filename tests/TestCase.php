<?php

namespace Tests;

use AllowDynamicProperties;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Skay1994\MyFramework\Application;
use Skay1994\MyFramework\Container;

abstract class TestCase extends BaseTestCase
{
    protected Application $app;

    protected Container $container;

    protected function setUp(): void
    {
        $this->app = new Application();
        $this->app->run();
        $this->container = Container::getInstance();
        parent::setUp();
    }
}
