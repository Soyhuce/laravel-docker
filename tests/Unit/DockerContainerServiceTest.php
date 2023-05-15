<?php

/** @covers \Soyhuce\Docker\Services\DockerContainerService */

namespace Test\Unit;

use Mockery;
use Soyhuce\Docker\Data\ContainerCreateItem;
use Soyhuce\Docker\Services\DockerContainerService;

beforeEach(function (): void {
    config()->set(['docker.driver' => 'api']);
    $this->mock = Mockery::mock(DockerContainerService::class);
});

test('container is created', function (): void {
    $this->mock->shouldReceive('create')
        ->withArgs(static function (string $imageName, string $containerName) {
            return $imageName === 'soyhuce/image:latest' && $containerName == 'mon-image';
        })
        ->andReturn(ContainerCreateItem::from([
            'Id' => '0123456789',
            'Warnings' => null,
        ]))
        ->once();

    app()->bind(DockerContainerService::class, fn () => $this->mock);

    $response = app(DockerContainerService::class)->create('soyhuce/image:latest', 'mon-image');

    expect($response)
        ->toBeInstanceOf(ContainerCreateItem::class)
        ->toEqual(ContainerCreateItem::from([
            'id' => '0123456789',
            'warnings' => null,
        ]));
});

test('container is started', function (): void {
    $this->mock->shouldReceive('start')
        ->withArgs(static function (string $uuid) {
            return $uuid === '123456789';
        })
        ->andReturn(true)
        ->once();

    app()->bind(DockerContainerService::class, fn () => $this->mock);

    $response = app(DockerContainerService::class)->start('123456789');

    expect($response)->toBeTrue();
});
