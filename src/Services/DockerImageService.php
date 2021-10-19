<?php

namespace Soyhuce\Docker\Services;

use Illuminate\Support\Collection;
use Soyhuce\Docker\DTO\ImageItem;

class DockerImageService extends DockerService
{
    /**
     * @return \Illuminate\Support\Collection<\Soyhuce\Docker\DTO\ImageItem>
     */
    public function all(): Collection
    {
        $response = $this->driver()
            ->asGet()
            ->send('/images/json');

        return new Collection(array_map(static fn ($item) => ImageItem::fromResponse($item), $response));
    }

    public function create(string $imageName, string $tag = 'latest'): bool
    {
        $this->driver()
            ->asPost()
            ->send(
                '/images/create',
                ['fromImage' => $imageName, 'tag' => $tag]
            );

        return true;
    }

    public function remove(string $imageName, array $options = []): bool
    {
        $this->driver()
            ->asDelete()
            ->send(
                "/images/{$imageName}",
                $options
            );

        return true;
    }
}
