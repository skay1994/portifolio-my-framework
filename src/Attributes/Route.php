<?php

namespace Skay1994\MyFramework\Attributes;

use Attribute;
use Skay1994\MyFramework\Facades\Route as RouteFacade;

#[Attribute(Attribute::TARGET_CLASS|Attribute::TARGET_METHOD)]
class Route
{
    /**
     * Constructor for the Route class.
     *
     * @param string $path The path for the route. Default is '/'.
     * @param string $group The group for the route. Default is an empty string.
     * @param string $prefix The prefix for the route. Default is an empty string.
     * @param string $name The name for the route. Default is an empty string.
     * @param array|string $methods The methods for the route. Default is an empty array.
     * @param array $options The options for the route. Default is an empty array.
     */
    public function __construct(
        string $path = '/',
        string $group = '',
        string $prefix = '',
        string $name = '',
        array|string $methods = [],
        array $options = [],
    )
    {}
}