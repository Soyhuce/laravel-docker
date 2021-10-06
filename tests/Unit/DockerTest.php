<?php

namespace Test\Unit;

use InvalidArgumentException;
use Soyhuce\Docker\Drivers\ApiDockerDriver;
use Soyhuce\Docker\Drivers\Docker;
use Soyhuce\Docker\Drivers\SocketDockerDriver;
use Test\TestCase;

/**
 * @coversDefaultClass \Soyhuce\Docker\Drivers\Docker
 */
class DockerTest extends TestCase
{
    /**
     * @test
     * @covers ::createApiDriver
     */
    public function apiDriverCanBeCreated(): void
    {
        $driver = app(Docker::class)->driver('api');

        $this->assertInstanceOf(ApiDockerDriver::class, $driver);
    }

    /**
     * @test
     * @covers ::createSocketDriver
     */
    public function socketDriverCanBeCreated(): void
    {
        $driver = app(Docker::class)->driver('socket');

        $this->assertInstanceOf(SocketDockerDriver::class, $driver);
    }

    /**
     * @test
     * @covers ::driver
     */
    public function notFoundDriverThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Driver [foo] is not supported.');

        app(Docker::class)->driver('foo');
    }

    /**
     * @test
     * @covers ::driver
     */
    public function driverIsASingleton(): void
    {
        $first = app(Docker::class)->driver();
        $second = app(Docker::class)->driver();

        $this->assertSame($first, $second);
    }
}
