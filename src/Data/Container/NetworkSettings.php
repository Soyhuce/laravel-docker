<?php declare(strict_types=1);

namespace Soyhuce\Docker\Data\Container;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class NetworkSettings extends Data
{
    /**
     * @param array<string, EndpointSettings> $networks
     */
    public function __construct(
        #[MapInputName('Networks')]
        public array $networks,
    ) {
    }
}
