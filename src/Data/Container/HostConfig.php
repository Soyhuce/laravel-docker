<?php declare(strict_types=1);

namespace Soyhuce\Docker\Data\Container;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class HostConfig extends Data
{
    /**
     * @param array<string, string> $annotations
     */
    public function __construct(
        #[MapInputName('NetworkMode')]
        public string $networkMode,
        #[MapInputName('Annotations')]
        public ?array $annotations = null,
    ) {
    }
}
