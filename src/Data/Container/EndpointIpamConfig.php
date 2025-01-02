<?php declare(strict_types=1);

namespace Soyhuce\Docker\Data\Container;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class EndpointIpamConfig extends Data
{
    /**
     * @param array<int, string> $linkLocalIPs
     */
    public function __construct(
        #[MapInputName('IPv4Address')]
        public string $iPv4Address,
        #[MapInputName('IPv6Address')]
        public string $iPv6Address,
        #[MapInputName('LinkLocalIPs')]
        public array $linkLocalIPs,
    ) {
    }
}
