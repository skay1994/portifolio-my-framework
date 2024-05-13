<?php

use Skay1994\MyFramework\Exceptions\Route\NotFoundException;
use Skay1994\MyFramework\Facades\Router;
use Skay1994\MyFramework\Router\Route;
use Skay1994\MyFramework\Router\RouteCollection;

beforeEach(function () {
    Router::clearRoutes();
});

it('It can add a new route', function () {
    $collection = new RouteCollection();

    $route = new Route('GET', ['path' => '/', 'handle' => 'index', 'use' => 'HomeController']);

    $collection->put('GET', $route);

    expect($collection->getRoutes('get'))
        ->toHaveCount(1)
        ->and(current($collection->getRoutes('get')))->toEqual($route);
});

it('It can add a new route with multiple methods', function () {
    $collection = new RouteCollection();

    $routeGet = new Route('GET', ['path' => '/', 'handle' => 'index', 'use' => 'HomeController']);
    $routePost = new Route('GET', ['path' => '/', 'handle' => 'index', 'use' => 'HomeController']);

    $collection->put('GET', $routeGet);
    $collection->put('POST', $routePost);

    expect($collection->getRoutes('get'))
        ->toHaveCount(1)
        ->and($collection->getRoutes('post'))
        ->toHaveCount(1)
        ->and(current($collection->getRoutes('get')))->toEqual($routeGet)
        ->and(current($collection->getRoutes('post')))->toEqual($routePost);
});

it('It can recovery route by URI', function () {
    $collection = new RouteCollection();

    $route = new Route('GET', ['path' => '/some/uri', 'handle' => 'index', 'use' => 'HomeController']);

    $collection->put('GET', $route);

    expect($collection->findRoute('/some/uri', 'get'))
        ->toEqual($route);
});

it('It can not recovery route by invalid URI', function () {
    $collection = new RouteCollection();
    $collection->findRoute('/some/uri', 'get');
})->throws(NotFoundException::class);

it('It can not recovery route by invalid http method', function () {
    $collection = new RouteCollection();

    $route = new Route('POST', ['path' => '/some/uri', 'handle' => 'index', 'use' => 'HomeController']);

    $collection->put('POST', $route);

    $collection->findRoute('/some/uri', 'get');
})->throws(NotFoundException::class);

it('It can add a controller route', function () {
    $collection = new RouteCollection();

    $controllerRoute = [
        'use' => 'HomeController',
        'handle' => 'index'
    ];

    $routers = [
        [
            'methods' => ['get'],
            'path' => '/',
            'use' => 'HomeController',
            'handle' => 'index'
        ],
        [
            'methods' => ['post'],
            'path' => '/',
            'use' => 'HomeController',
            'handle' => 'index'
        ],
        [
            'methods' => ['put'],
            'path' => '/edit',
            'use' => 'HomeController',
            'handle' => 'edit'
        ]
    ];

    $collection->addController($controllerRoute, $routers);

    unset($routers[0]['methods'], $routers[1]['methods'], $routers[2]['methods']);

    expect($collection->getRoutes())
        ->toHaveCount(3)->toHaveKeys(['GET', 'POST', 'PUT'])
        ->and(current($collection->getRoutes('get')))
        ->getPath()->toEqual($routers[0]['path'])
        ->and(current($collection->getRoutes('post')))
        ->getPath()->toEqual($routers[1]['path'])
        ->and(current($collection->getRoutes('put')))
        ->getPath()->toEqual($routers[2]['path']);
});

// Parameters Tests

it('It can resolve route with single parameter', function () {
    $route = new Route('GET', ['path' => 'users/{id}', 'use' => 'HomeController', 'handle' => 'index']);
    $collection = new RouteCollection();
    $collection->put('GET', $route);

    expect($collection->findRoute('/users/1', 'get'))->toEqual($route)
        ->getController()->toEqual('HomeController')
        ->getHandle()->toEqual('index')
        ->getParameters()->toBeArray()->toMatchArray(['id' => 1])
        ->pathRegex()->toEqual('/users\/(\w+)$/');
});

it('It can resolve route with multiple parameter', function () {
    $route = new Route('GET', ['path' => 'users/{id}/reports/{report_type}', 'use' => 'HomeController', 'handle' => 'index']);
    $collection = new RouteCollection();
    $collection->put('GET', $route);

    expect($collection->findRoute('/users/1/reports/yearly', 'get'))->toEqual($route)
        ->getController()->toEqual('HomeController')
        ->getHandle()->toEqual('index')
        ->getParameters()->toBeArray()->toMatchArray(['id' => 1, 'report_type' => 'yearly'])
        ->pathRegex()->toEqual('/users\/(\w+)\/reports\/(\w+)$/');
});

it('It can resolve route with optional parameter', function () {
    $route = new Route('GET', ['path' => 'users/{id}/reports/{?report_type}', 'use' => 'HomeController', 'handle' => 'index']);
    $collection = new RouteCollection();
    $collection->put('GET', $route);

    expect($collection->findRoute('/users/1/reports/yearly', 'get'))->toEqual($route)
        ->getController()->toEqual('HomeController')
        ->getHandle()->toEqual('index')
        ->getParameters()->toBeArray()->toMatchArray(['id' => 1, 'report_type' => 'yearly'])
        ->pathRegex()->toEqual('/users\/(\w+)\/reports\/?(\w*)$/');
});

it('It can resolve route only with optional parameter', function () {
    $route = new Route('GET', ['path' => 'reports/{?report_type}', 'use' => 'HomeController', 'handle' => 'index']);
    $collection = new RouteCollection();
    $collection->put('GET', $route);

    expect($collection->findRoute('reports', 'get'))->toEqual($route)
        ->getController()->toEqual('HomeController')
        ->getHandle()->toEqual('index')
        ->getParameters()->toBeArray()->toMatchArray(['report_type' => ''])
        ->pathRegex()->toEqual('/reports\/?(\w*)$/');
});

it('It cannot resolve route by missing parameter', function () {
    $route = new Route('GET', ['path' => 'users/{id}', 'use' => 'HomeController', 'handle' => 'index']);
    $collection = new RouteCollection();
    $collection->put('GET', $route);

    $collection->findRoute('/users', 'get');
})->throws(NotFoundException::class);