<?php declare(strict_types=1);

/** @covers \Soyhuce\Docker\Services\DockerImageService */

namespace Test\Unit;

use Illuminate\Support\Facades\Http;
use Soyhuce\Docker\Data\ImageItem;
use Soyhuce\Docker\Services\DockerImageService;

beforeEach(function (): void {
    config()->set(['docker.driver' => 'api']);
});

test('image is created', function (): void {
    $mock = $this->mock(DockerImageService::class);
    $mock->shouldReceive('create')
        ->withArgs(static function (string $imageName, string $containerName) {
            return $imageName === 'soyhuce/image:latest' && $containerName == 'mon-image';
        })
        ->andReturn(true)
        ->once();

    $response = app(DockerImageService::class)->create('soyhuce/image:latest', 'mon-image');

    expect($response)->toBeTrue();
});

test('image is removed', function (): void {
    $mock = $this->mock(DockerImageService::class);
    $mock->shouldReceive('remove')
        ->withArgs(static function (string $imageName) {
            return $imageName === 'soyhuce/image:latest';
        })
        ->andReturn(true)
        ->once();

    $response = app(DockerImageService::class)->remove('soyhuce/image:latest');

    expect($response)->toBeTrue();
});

test('images are retrieved', function (): void {
    Http::fakeSequence()
        ->pushFile(__DIR__ . '/stubs/images.json');

    $response = app(DockerImageService::class)->all();

    expect($response)->toEqual(collect([
        ImageItem::from([
            'id' => 'sha256:ec3f0931a6e6b6855d76b2d7b0be30e81860baccd891b2e243280bf1cd8ad710',
            'created' => '1644009612',
            'repoTags' => [
                'example:1.0',
                'example:latest',
                'example:stable',
                'internal.registry.example.com:5000/example:1.0',
            ],
            'size' => 172_064_416,
        ]),
    ]));
});
