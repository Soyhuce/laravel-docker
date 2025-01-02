<?php declare(strict_types=1);

namespace Soyhuce\Docker\Data\Container;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class Port extends Data
{
    public function __construct(
        #[MapInputName('PrivatePort')]
        public int $privatePort,
        #[MapInputName('Type')]
        public string $type,
        #[MapInputName('IP')]
        public ?string $ip = null,
        #[MapInputName('PublicPort')]
        public ?int $publicPort = null,
    ) {
    }
}
