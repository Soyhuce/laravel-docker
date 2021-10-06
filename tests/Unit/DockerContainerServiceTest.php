<?php

namespace Test\Unit;

use Mockery;
use Soyhuce\Docker\DTO\ContainerCreateItem;
use Soyhuce\Docker\Services\DockerContainerService;
use Test\TestCase;

/**
 * @coversDefaultClass \Soyhuce\Docker\Services\DockerContainerService
 */
class DockerContainerServiceTest extends TestCase
{
    /** @var \Mockery\LegacyMockInterface|\Mockery\MockInterface|\Soyhuce\Docker\Services\DockerContainerService */
    private $mock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setConfigs();

        $this->mock = Mockery::mock(DockerContainerService::class);
    }

    /**
     * @test
     * @covers ::create
     */
    public function containerIsCreated(): void
    {
        $this->mock->shouldReceive('create')
            ->withArgs(static function (string $imageName, string $containerName) {
                return $imageName === 'soyhuce/image:latest' && $containerName == 'mon-image';
            })
            ->andReturn(new ContainerCreateItem([
                'Id' => '0123456789',
                'Warnings' => null,
            ]))
            ->once();

        app()->bind(DockerContainerService::class, fn () => $this->mock);

        $response = app(DockerContainerService::class)->create('soyhuce/image:latest', 'mon-image');

        $this->assertInstanceOf(ContainerCreateItem::class, $response);
        $this->assertEquals([
            'id' => '0123456789',
            'warnings' => null,
        ], $response->toArray());
    }

    /**
     * @test
     * @covers ::create
     */
    public function containerIsStarted(): void
    {
        $this->mock->shouldReceive('start')
            ->withArgs(static function (string $uuid) {
                return $uuid === '123456789';
            })
            ->andReturn(true)
            ->once();

        app()->bind(DockerContainerService::class, fn () => $this->mock);

        $response = app(DockerContainerService::class)->start('123456789');

        $this->assertTrue($response);
    }

    private function setConfigs(): void
    {
        config()->set([
            'docker.drivers.socket.scheme' => 'http',
            'docker.drivers.socket.version' => null,
        ]);
    }
}
