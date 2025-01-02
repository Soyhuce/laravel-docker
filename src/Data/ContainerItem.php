<?php declare(strict_types=1);

namespace Soyhuce\Docker\Data;

use Carbon\Carbon;
use Soyhuce\Docker\Data\Container\HostConfig;
use Soyhuce\Docker\Data\Container\NetworkSettings;
use Soyhuce\Docker\Data\Container\Port;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class ContainerItem extends Data
{
    /**
     * @param array<int, string> $names
     * @param array<int, Port> $ports
     * @param array<string, string> $labels
     */
    public function __construct(
        #[MapInputName('Id')]
        public string $id,
        #[MapInputName('Names')]
        public array $names,
        #[MapInputName('Image')]
        public string $image,
        #[MapInputName('ImageID')]
        public string $imageId,
        #[MapInputName('Command')]
        public string $command,
        #[MapInputName('Created')]
        #[WithCast(DateTimeInterfaceCast::class, format: 'U')]
        public Carbon $created,
        #[MapInputName('Ports')]
        public array $ports,
        #[MapInputName('Labels')]
        public array $labels,
        #[MapInputName('State')]
        public string $state,
        #[MapInputName('Status')]
        public string $status,
        #[MapInputName('HostConfig')]
        public HostConfig $hostConfig,
        #[MapInputName('NetworkSettings')]
        public NetworkSettings $networkSettings,
        #[MapInputName('SizeRw')]
        public ?int $sizeRw = null,
        #[MapInputName('SizeRootFs')]
        public ?int $sizeRootFs = null,
    ) {
    }
}
