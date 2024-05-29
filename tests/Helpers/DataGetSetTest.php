<?php

// data_get Tests

it('It can recovery first value from array', function () {
    $values = [
        'key1' => 'value1',
        'key2' => 'value2',
    ];

    expect(data_get($values, '{first}'))->toBe('value1');
});

it('It can recovery value from array', function () {
    $values = [
        'key1' => 'value1',
        'key2' => 'value2',
    ];

    expect(data_get($values, 'key1'))->toBe('value1');
});

it('It can recovery last value from array', function () {
    $values = [
        'key1' => 'value1',
        'key2' => 'value2',
    ];

    expect(data_get($values, '{last}'))->toBe('value2');
});

it('It can recovery first value with dot notation from array', function () {
    $values = [
        'key1' => [
            'key2' => 'value1',
            'key3' => 'value2',
            'key4' => 'value3',
        ]
    ];

    expect(data_get($values, 'key1.{first}'))->toBe('value1');
});

it('It can recovery value with dot notation from array', function () {
    $values = [
        'key1' => [
            'key2' => 'value1',
        ]
    ];

    expect(data_get($values, 'key1.key2'))->toBe('value1');
});

it('It can recovery last value with dot notation from array', function () {
    $values = [
        'key1' => [
            'key2' => 'value1',
            'key3' => 'value2',
            'key4' => 'value3',
        ]
    ];

    expect(data_get($values, 'key1.{last}'))->toBe('value3');
});

it('It cannot recovery value from array with dot notation by missing value', function () {
    $values = [
        'key1' => 'value1',
    ];

    expect(data_get($values, 'key1.key2'))->toBeNull();
});

it('It cannot recovery value from array with dot notation and receive default value', function () {
    $values = [
        'key1' => 'value1',
    ];

    expect(data_get($values, 'key1.key2', 'default'))->toBe('default');
});

it('It cannot recovery value from array with dot notation and receive default value by clousure', function () {
    $values = [
        'key1' => 'value1',
    ];

    expect(data_get($values, 'key1.key2', static fn () => 'default'))->toBe('default');
});

// data_set Tests

it('It can set value in array', function () {
    $values = [
        'key1' => 'value1',
        'key2' => 'value2',
    ];

    data_set($values, 'key3', 'value3');

    expect($values)->toEqual([
        'key1' => 'value1',
        'key2' => 'value2',
        'key3' => 'value3',
    ]);
});

it('It can set value in array with dot notation', function () {
    $values = [
        'key1' => [ 'key2' => 'value2' ],
        'key2' => 'value2',
    ];

    data_set($values, 'key1.key3', 'value3');

    expect($values)->toEqual([
        'key1' => [ 'key2' => 'value2', 'key3' => 'value3' ],
        'key2' => 'value2',
    ]);
});

it('It can overwrite value in array with dot notation', function () {
    $values = [
        'key1' => [ 'key2' => 'value2', 'key3' => null ],
        'key2' => 'value2',
    ];

    data_set($values, 'key1.key3', 'value3');

    expect($values)->toEqual([
        'key1' => [ 'key2' => 'value2', 'key3' => 'value3' ],
        'key2' => 'value2',
    ]);
});

it('It can overwrite nested value in array with dot notation', function () {
    $values = [
        'key1' => [
            [ 'key2' => 'value2', 'key3' => null ],
            [ 'key2' => 'value2', 'key3' => null ]
        ],
        'key2' => 'value2',
    ];

    data_set($values, 'key1.*.key3', 'value3');

    expect($values['key1'][0]['key3'])->toEqual('value3')
        ->and($values['key1'][1]['key3'])->toEqual('value3');
});

it('It can set value and receive array from null variable ', function () {
    $values = null;

    data_set($values, 'key1', 'value1');

    expect($values)->toEqual([
        'key1' => 'value1',
    ]);
});