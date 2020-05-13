<?php

namespace Soyhuce\Docker\Services;

use Soyhuce\Docker\DTO\ContainerCreateItem;

class DockerContainerService extends DockerService
{
    public function create(string $imageName, string $containerName, array $options = []): ContainerCreateItem
    {
        $response = $this->driver()
            ->asPost()
            ->send(
                '/containers/create',
                ['name' => $containerName],
                array_merge(
                    $options,
                    [
                        'Image' => $imageName,
                        'HostConfig' => ['AutoRemove' => true],
                    ]
                )
            );

        return new ContainerCreateItem($response);
    }

    public function start(string $containerId): bool
    {
        $this->driver()
            ->asPost()
            ->send(
                "/containers/{$containerId}/start"
            );

        return true;
    }

    public function stop(string $containerId): bool
    {
        $this->driver()
            ->asPost()
            ->send(
                "/containers/{$containerId}/stop"
            );

        return true;
    }
}
