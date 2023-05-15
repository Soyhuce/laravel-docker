<?php

/** @covers \Soyhuce\Docker\Drivers\Docker */

namespace Test\Unit;

use InvalidArgumentException;
use Soyhuce\Docker\Drivers\ApiDockerDriver;
use Soyhuce\Docker\Drivers\Docker;
use Soyhuce\Docker\Drivers\SocketDockerDriver;

test('api driver created', function (): void {
    $driver = app(Docker::class)->driver('api');

    expect($driver)->toBeInstanceOf(ApiDockerDriver::class);
});

test('socket driver created', function (): void {
    $driver = app(Docker::class)->driver('socket');

    expect($driver)->toBeInstanceOf(SocketDockerDriver::class);
});
test('driver not found throw exception', function (): void {
    app(Docker::class)->driver('foo');
})->throws(InvalidArgumentException::class, 'Driver [foo] is not supported.');

test('driver is a singleton', function (): void {
    $first = app(Docker::class)->driver();
    $second = app(Docker::class)->driver();

    expect($first)->toEqual($second);
});
