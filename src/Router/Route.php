<?php

namespace Skay1994\MyFramework\Router;

class Route
{
    public function __construct(
        protected string $httpMethod,
        protected array $details
    )
    {
    }
}