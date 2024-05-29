<?php

use Skay1994\MyFramework\Facades\Container;

afterEach(function () {
    Container::resetDefault();
});

it('It can load default config', function () {
    expect(config('app.app_name'))->toBe('My Framework');
});

it('It can load app config and override default config', function () {
    $this->app->setBasePath(__DIR__);
    Container::resetDefault();
    $this->app->run();

    expect(config('app.app_name'))
        ->toBe('MyFramework Test');
});

it('It can change a app config', function () {
    config(['app.app_name' => 'MyFramework Custom']);

    expect(config('app.app_name'))
        ->toBe('MyFramework Custom');
});


it('It can add a new app config', function () {
    config(['paymentmethod.token' => '123456789']);

    expect(config('paymentmethod.token'))
        ->toBe('123456789')
        ->and(config('paymentmethod'))->toBeArray()
        ->toMatchArray([
                'token' => '123456789'
            ]);
});

it('It can add a multiple new app config', function () {
    config([
        'paymentmethod.token' => '123456789',
        'paymentmethod.secret' => '123456789'
    ]);

    expect(config('paymentmethod.token'))->toBe('123456789')
        ->and(config('paymentmethod.secret'))->toBe('123456789')
        ->and(config('paymentmethod'))->toBeArray()
        ->toMatchArray([
                'token' => '123456789',
                'secret' => '123456789'
            ]);
});

it('It can receive default value when key is not found', function () {
    expect(config('paymentmethod.token', '123456789'))
        ->toBe('123456789');
});

it('It can receive null value when key is not found', function () {
    expect(config('paymentmethod.token'))
        ->toBeNull();
});