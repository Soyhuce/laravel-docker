<?php

namespace Soyhuce\Docker\Services;

use Soyhuce\Docker\Collections\ImageItemCollection;

class DockerImageService extends DockerService
{
    /**
     * @return \Soyhuce\Docker\Collections\ImageItemCollection<\Soyhuce\Docker\DTO\ImageItem>
     */
    public function all(): ImageItemCollection
    {
        $response = $this->driver()
            ->asGet()
            ->send('/images/json');

        return ImageItemCollection::fromResponse($response);
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
