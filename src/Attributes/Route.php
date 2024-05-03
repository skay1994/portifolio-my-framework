<?php

namespace Skay1994\MyFramework\Attributes;

use Attribute;
use Skay1994\MyFramework\Facades\Route as RouteFacade;

#[Attribute(Attribute::TARGET_CLASS|Attribute::TARGET_METHOD)]
class Route
{
    public function __construct(
        string $path = '/',
        string $group = '',
        string $prefix = '',
        string $name = '',
        array $methods = [],
        array $options = [],
    )
    {}
}