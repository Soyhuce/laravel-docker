<?php

/** @covers \Soyhuce\Docker\Services\DockerContainerService */

namespace Test\Unit;

use Soyhuce\Docker\Data\ContainerCreateItem;
use Soyhuce\Docker\Services\DockerContainerService;

beforeEach(function (): void {
    config()->set(['docker.driver' => 'api']);
});

test('container is created', function (): void {
    $mock = $this->mock(DockerContainerService::class);
    $mock->shouldReceive('create')
        ->withArgs(static function (string $imageName, string $containerName) {
            return $imageName === 'soyhuce/image:latest' && $containerName == 'mon-image';
        })
        ->andReturn(ContainerCreateItem::from([
            'Id' => '0123456789',
            'Warnings' => null,
        ]))
        ->once();

    $response = app(DockerContainerService::class)->create('soyhuce/image:latest', 'mon-image');

    expect($response)
        ->toBeInstanceOf(ContainerCreateItem::class)
        ->toEqual(ContainerCreateItem::from([
            'id' => '0123456789',
            'warnings' => null,
        ]));
});

test('container is started', function (): void {
    $mock = $this->mock(DockerContainerService::class);
    $mock->shouldReceive('start')
        ->withArgs(static function (string $uuid) {
            return $uuid === '123456789';
        })
        ->andReturn(true)
        ->once();

    $response = app(DockerContainerService::class)->start('123456789');

    expect($response)->toBeTrue();
});
