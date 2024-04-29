<?php

namespace Tests;

use AllowDynamicProperties;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Skay1994\MyFramework\Application;
use Skay1994\MyFramework\Container;

/**
 * @property-read Application $app
 * @property-read Container $container
 */
#[AllowDynamicProperties]
abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        $this->app = new Application();
        $this->app->run();
        $this->container = Container::getInstance();
        parent::setUp();
    }
}
