<?php

use Skay1994\MyFramework\Exceptions\Filesystem\FileNotFoundException;
use Skay1994\MyFramework\Facades\App;

beforeEach(function () {
    App::setBasePath(__DIR__);
});

it('can render a view', function () {
    $content = view('welcome');

    expect($content)->toBe('Hello World!');
});

it('can render a view with view data', function () {
    $content = view('welcome_var', [ 'name' => 'John Doe']);

    expect($content)->toBe('Hello World, John Doe');
});

it('can render a view with include another view', function () {
    $content = view('welcome_layout');

    $template = <<<HTML
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Header Template</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
<p>Hello World!</p>
</body>
HTML;

    expect($content)->toBe($template);
});

it('throw a exception on view file not found', function () {
    view('not_found_view');
})->throws(FileNotFoundException::class);
