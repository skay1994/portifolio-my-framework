<?php

use Skay1994\MyFramework\Router\Route;
use Tests\Router\TestControllers\ControllerWithoutMethodAttribute;
use Tests\Router\TestControllers\ControllerWithPath;
use Tests\Router\TestControllers\ControllerWithPrefix;
use Skay1994\MyFramework\Facades\App;
use Skay1994\MyFramework\Facades\Container;
use Skay1994\MyFramework\Facades\Router;
use Tests\Router\TestControllers\ControllerWithRouteParameter;

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
        ->toBeInstanceOf(Route::class)
        ->getPath()->toEqual('/search/')
        ->getController()->toEqual(ControllerWithPrefix::class)
        ->getHandle()->toEqual('index')

        ->and($router->getRouters('post')[0])
        ->toBeInstanceOf(Route::class)
        ->getPath()->toEqual('/search/')
        ->getController()->toEqual(ControllerWithPrefix::class)
        ->getHandle()->toEqual('updateSearch');
});

it('Register controller path and without prefix appends all internal routers', function () {
    $router = Container::get('router');

    (new ReflectionClass($router))
        ->getMethod('buildRoutes')
        ->invoke($router, ControllerWithPath::class);

    expect($router->getRouters())
        ->toHaveCount(2)
        ->and($router->getRouters('get')[0])
        ->toBeInstanceOf(Route::class)
        ->getPath()->toEqual('/search/')
        ->getController()->toEqual(ControllerWithPath::class)
        ->getHandle()->toEqual('index')

        ->and($router->getRouters('post')[0])
        ->toBeInstanceOf(Route::class)
        ->getPath()->toEqual('/search/')
        ->getController()->toEqual(ControllerWithPath::class)
        ->getHandle()->toEqual('updateSearch');
});

it('find router by path and method', function () {
    $router = Container::get('router');

    (new ReflectionClass($router))
        ->getMethod('buildRoutes')
        ->invoke($router, ControllerWithPath::class);

    $response = Router::handle('search', 'get');

    expect($response)
        ->not->toBeNull()
        ->toEqual('search users');
});

it('find router with path using parameter', function () {
    $router = Container::get('router');

    (new ReflectionClass($router))
        ->getMethod('buildRoutes')
        ->invoke($router, ControllerWithRouteParameter::class);

    $response = Router::handle('search/999', 'get');

    expect($response)
        ->not->toBeNull()
        ->toEqual('My id is 999');
});

it('find router with path using optional parameter ', function () {
    $router = Container::get('router');

    (new ReflectionClass($router))
        ->getMethod('buildRoutes')
        ->invoke($router, ControllerWithRouteParameter::class);

    $response = Router::handle('search', 'post');

    expect($response)
        ->not->toBeNull()
        ->toEqual('My id is WithoutID');
});

it('register controller with attribute but without methods with attribute', function () {
    $router = Container::get('router');

    (new ReflectionClass($router))
        ->getMethod('buildRoutes')
        ->invoke($router, ControllerWithoutMethodAttribute::class);

    expect($router->getRouters())
        ->toBeArray()->toBeEmpty();
});
