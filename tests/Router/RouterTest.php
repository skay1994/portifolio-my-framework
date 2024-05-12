<?php

use Tests\Router\TestControllers\ControllerWithoutMethodAttribute;
use Tests\Router\TestControllers\ControllerWithPath;
use Tests\Router\TestControllers\ControllerWithPrefix;
use Skay1994\MyFramework\Facades\App;
use Skay1994\MyFramework\Facades\Container;
use Skay1994\MyFramework\Facades\Router;

beforeEach(function () {
    Router::clearRoutes();
    Router::setNamespaceReplacer([
        'search' => [
            App::joinPaths(dirname(__DIR__)), '', '.php', DIRECTORY_SEPARATOR, '/'
        ],
        'replace' => ['\Tests', '\Tests', '', '\\', '\\']
    ]);
});

it('find and registers all controller in folder', function () {
    $path = App::joinPaths(dirname(__DIR__), 'Router', 'Controllers');
    Router::registerRouters($path);

    expect(Router::getRouters())
        ->toHaveCount(2)
        ->and(Router::getRouters('get'))->toHaveCount(2)
        ->and(Router::getRouters('post'))->toHaveCount(1);
});

it('Register controller with prefix appends all internal routers', function () {
    $router = Container::get('router');

    (new ReflectionClass($router))
        ->getMethod('buildRoutes')
        ->invoke($router, ControllerWithPrefix::class);

    expect($router->getRouters())
        ->toHaveCount(2)
        ->and($router->getRouters('get')[0])
        ->toMatchArray([
            'path' => 'search/',
            'use' => ControllerWithPrefix::class,
            'handle' => 'index',
        ])
        ->and($router->getRouters('post')[0])
        ->toMatchArray([
            'path' => 'search/',
            'use' => ControllerWithPrefix::class,
            'handle' => 'updateSearch',
        ])
    ;
});

it('Register controller path and without prefix appends all internal routers', function () {
    $router = Container::get('router');

    (new ReflectionClass($router))
        ->getMethod('buildRoutes')
        ->invoke($router, ControllerWithPath::class);

    expect($router->getRouters())
        ->toHaveCount(2)
        ->and($router->getRouters('get')[0])
        ->toMatchArray([
            'path' => 'search/',
            'use' => ControllerWithPath::class,
            'handle' => 'index',
        ])
        ->and($router->getRouters('post')[0])
        ->toMatchArray([
            'path' => 'search/',
            'use' => ControllerWithPath::class,
            'handle' => 'updateSearch',
        ])
    ;
});

it('find router by path and method', function () {
    $router = Container::get('router');

    (new ReflectionClass($router))
        ->getMethod('buildRoutes')
        ->invoke($router, ControllerWithPath::class);

    $response = Router::handle('search', 'get');

    expect($response)
        ->not->toBeNull()
        ->toEqual('search users')
    ;
});

it('register controller with attribute but without methods with attribute', function () {
    $router = Container::get('router');

    (new ReflectionClass($router))
        ->getMethod('buildRoutes')
        ->invoke($router, ControllerWithoutMethodAttribute::class);

    expect($router->getRouters())
        ->toBeArray()->toBeEmpty();
});
