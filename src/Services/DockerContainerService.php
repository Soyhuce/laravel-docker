<?php declare(strict_types=1);

namespace Soyhuce\Docker\Services;

use Soyhuce\Docker\Data\ContainerCreateItem;

class DockerContainerService extends DockerService
{
    public function create(string $imageName, string $containerName, array $options = []): ContainerCreateItem
    {
        $response = $this->driver()
            ->asPost()
            ->send(
                '/containers/create',
                ['name' => $containerName],
                [
                    ...$options,
                    'Image' => $imageName,
                    'HostConfig' => [
                        'AutoRemove' => true,
                        'ExtraHosts' => config('docker.extra_hosts'),
                    ],
                ]
            );

        return ContainerCreateItem::from($response);
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

    public function wait(string $containerId): bool
    {
        $this->driver()
            ->asPost()
            ->send("/containers/{$containerId}/wait");

        return true;
    }

    public function delete(string $containerId): bool
    {
        $this->driver()
            ->asDelete()
            ->send("/containers/{$containerId}");

        return true;
    }
}
