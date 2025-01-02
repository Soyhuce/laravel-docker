<?php declare(strict_types=1);

namespace Soyhuce\Docker\Data\Container;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class EndpointSettings extends Data
{
    /**
     * @param array<int, string>|null $links
     * @param array<int, string> $aliases
     * @param array<string, string>|null $driverOpts
     * @param array<int, string>|null $dnsNames
     */
    public function __construct(
        #[MapInputName('IPAMConfig')]
        public ?EndpointIpamConfig $ipamConfig,
        #[MapInputName('Links')]
        public ?array $links,
        #[MapInputName('MacAddress')]
        public string $macAddress,
        #[MapInputName('Aliases')]
        public ?array $aliases,
        #[MapInputName('NetworkID')]
        public string $networkId,
        #[MapInputName('EndpointID')]
        public string $endpointId,
        #[MapInputName('Gateway')]
        public string $gateway,
        #[MapInputName('IPAddress')]
        public string $ipAddress,
        #[MapInputName('IPPrefixLen')]
        public int $ipPrefixLen,
        #[MapInputName('IPv6Gateway')]
        public string $iPv6Gateway,
        #[MapInputName('GlobalIPv6Address')]
        public string $globalIPv6Address,
        #[MapInputName('GlobalIPv6PrefixLen')]
        public int $globalIPv6PrefixLen,
        #[MapInputName('DNSNames')]
        public ?array $dnsNames,
        #[MapInputName('DriverOpts')]
        public ?array $driverOpts = null,
    ) {
    }
}
