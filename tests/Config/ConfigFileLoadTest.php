<?php

use Skay1994\MyFramework\Config;
use Skay1994\MyFramework\Enums\ConfigSourceEnum;
use Skay1994\MyFramework\Exceptions\Filesystem\FileNotFoundException;
use Skay1994\MyFramework\Facades\Container;
use Skay1994\MyFramework\Filesystem;

afterEach(function () {
    \Skay1994\MyFramework\Facades\Config::reset();
});

afterAll(function () {
    \Skay1994\MyFramework\Facades\Config::reset();
});

it('It can load a config file from app config folder', function () {
    $this->app->setBasePath(__DIR__);
    $config = new Config($this->app, Container::get(Filesystem::class));
    $config->load('app.php');

    $configs = $config->get();

    expect($configs)
        ->toHaveKey('app')
        ->and($configs['app'])
        ->toHaveCount(4)
        ->toHaveKeys([
            'app_name', 'version', 'debug', 'environment',
        ])
        ->toMatchArray([
            'app_name' => 'MyFramework Test',
            'version' => '0.0.1',
            'debug' => false,
            'environment' => 'testing',
        ]);
});

it('It can load a config file from framework config folder', function () {
    $this->app->setBasePath(__DIR__);
    $config = new Config($this->app, Container::get(Filesystem::class));
    $config->load('app.php', ConfigSourceEnum::FRAMEWORK);

    $configs = $config->get();

    expect($configs)
        ->toHaveKey('app')
        ->and($configs['app'])
        ->toHaveCount(4)
        ->toHaveKeys([
            'app_name', 'version', 'debug', 'environment',
        ])
        ->toMatchArray([
            'app_name' => 'My Framework',
            'version' => '1.0.0',
            'debug' => false,
            'environment' => 'testing',
        ]);
});

it('It load app config file override previews framework config file', function () {
    $this->app->setBasePath(__DIR__);
    $config = new Config($this->app, Container::get(Filesystem::class));
    $config->load('app.php', ConfigSourceEnum::FRAMEWORK);
    $frameworkConfigs = $config->get('app');

    $config->load('app.php');
    $appConfigs = $config->get('app');

    expect($frameworkConfigs)
        ->not->toEqual($appConfigs)
        ->and($appConfigs)
        ->toHaveCount(4)
        ->toHaveKeys([
            'app_name', 'version', 'debug', 'environment',
        ])
        ->toMatchArray([
            'app_name' => 'MyFramework Test',
            'version' => '0.0.1',
            'debug' => false,
            'environment' => 'testing',
        ]);
});

it('It cannot load app config file for missing file', function () {
    $this->app->setBasePath(__DIR__);
    $config = new Config($this->app, Container::get(Filesystem::class));
    $config->load('missing.php');
})->throws(FileNotFoundException::class);

it('It cannot load framework config file for missing file', function () {
    $this->app->setBasePath(__DIR__);
    $config = new Config($this->app, Container::get(Filesystem::class));
    $config->load('missing.php', ConfigSourceEnum::FRAMEWORK);
})->throws(FileNotFoundException::class);

## getConfigFile method

it('It can required a php file to use as config', function () {
    $this->app->setBasePath(__DIR__);
    $config = new Config($this->app, Container::get(Filesystem::class));

    $values =(new ReflectionClass($config))
        ->getMethod('getConfigFile')
        ->invoke($config, ConfigSourceEnum::APP->path('app.php'));

    expect($values)
        ->toBeArray()
        ->toHaveCount(2)
        ->toHaveKeys([
            'app_name', 'version',
        ])
        ->toMatchArray([
            'app_name' => 'MyFramework Test',
            'version' => '0.0.1',
        ]);
});

it('It load config file but file not return an array and receive blank array', function () {
    $this->app->setBasePath(__DIR__);
    $config = new Config($this->app, Container::get(Filesystem::class));

    $values =(new ReflectionClass($config))
        ->getMethod('getConfigFile')
        ->invoke($config, ConfigSourceEnum::APP->path('non-array.php'));

    expect($values)
        ->toBeArray()
        ->toHaveCount(0);
});

it('It cannot load file by missing file and receive blank array', function () {
    $this->app->setBasePath(__DIR__);
    $config = new Config($this->app, Container::get(Filesystem::class));

    $values =(new ReflectionClass($config))
        ->getMethod('getConfigFile')
        ->invoke($config, ConfigSourceEnum::APP->path('blank.php'));

    expect($values)
        ->toBeArray()
        ->toHaveCount(0);
});