<?php

use Skay1994\MyFramework\Exceptions\Route\NotFoundException;
use Skay1994\MyFramework\Facades\Route;
use Skay1994\MyFramework\Router\RouteCollection;

beforeEach(function () {
    Route::clearRoutes();
});

it('It can add a new route', function () {
    $collection = new RouteCollection();
    $collection->put('GET', '/', 'HomeController', 'index');

    $route = [
        'path' => '/',
        'use' => 'HomeController',
        'handle' => 'index',
        'has_params' => false
    ];

    expect($collection->getRoutes('get'))
        ->toHaveCount(1)
        ->and(current($collection->getRoutes('get')))
        ->toMatchArray($route);
});

it('It can add a new route with multiple methods', function () {
    $collection = new RouteCollection();
    $collection->put('GET', '/', 'HomeController', 'index');
    $collection->put('POST', '/', 'HomeController', 'index');

    $route = [
        'path' => '/',
        'use' => 'HomeController',
        'handle' => 'index',
        'has_params' => false
    ];

    expect($collection->getRoutes('get'))
        ->toHaveCount(1)
        ->and($collection->getRoutes('post'))
        ->toHaveCount(1)
        ->and(current($collection->getRoutes('get')))
        ->toMatchArray($route)
        ->and(current($collection->getRoutes('post')))
        ->toMatchArray($route);
});

it('It can recovery route by URI', function () {
    $collection = new RouteCollection();
    $collection->put('GET', '/some/uri', 'HomeController', 'index');

    expect($collection->findRoute('/some/uri', 'get'))
        ->toBeArray()
        ->toMatchArray([
            'path' => '/some/uri',
            'use' => 'HomeController',
            'handle' => 'index',
            'has_params' => false
        ]);
});

it('It can not recovery route by invalid URI', function () {
    $collection = new RouteCollection();
    $collection->findRoute('/some/uri', 'get');
})->throws(NotFoundException::class);

it('It can not recovery route by invalid http method', function () {
    $collection = new RouteCollection();
    $collection->put('POST', '/some/uri', 'HomeController', 'index');
    $collection->findRoute('/some/uri', 'get');
})->throws(NotFoundException::class);

it('It can add a controller route', function () {
    $collection = new RouteCollection();

    $controllerRoute = [
        'use' => 'HomeController',
        'handle' => 'index',
        'has_params' => false
    ];

    $routers = [
        [
            'methods' => ['get'],
            'path' => '/',
            'use' => 'HomeController',
            'handle' => 'index',
            'has_params' => false
        ],
        [
            'methods' => ['post'],
            'path' => '/',
            'use' => 'HomeController',
            'handle' => 'index',
            'has_params' => false
        ],
        [
            'methods' => ['put'],
            'path' => '/edit',
            'use' => 'HomeController',
            'handle' => 'edit',
            'has_params' => false
        ]
    ];

    $collection->addController($controllerRoute, $routers);

    unset($routers[0]['methods'], $routers[1]['methods'], $routers[2]['methods']);

    expect($collection->getRoutes())
        ->toHaveCount(3)->toHaveKeys(['GET', 'POST', 'PUT'])
        ->and(current($collection->getRoutes('get')))
        ->toMatchArray($routers[0])
        ->and(current($collection->getRoutes('post')))
        ->toMatchArray($routers[1])
        ->and(current($collection->getRoutes('put')))
        ->toMatchArray($routers[2]);
});