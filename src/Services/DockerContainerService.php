<?php declare(strict_types=1);

namespace Soyhuce\Docker\Services;

use Illuminate\Support\Collection;
use Soyhuce\Docker\Data\ContainerCreateItem;
use Soyhuce\Docker\Data\ContainerItem;

class DockerContainerService extends DockerService
{
    /**
     * @param array<string, mixed> $options
     * @return Collection<int, ContainerItem>
     */
    public function all(array $options = []): Collection
    {
        $response = $this->driver()
            ->asGet()
            ->send('/containers/json', $options);

        return new Collection(array_map(static fn ($item) => ContainerItem::from($item), $response));
    }

    /**
     * @param array<string, mixed> $options
     */
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
