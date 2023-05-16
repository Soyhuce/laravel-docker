<?php

namespace Soyhuce\Docker\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class ContainerCreateItem extends Data
{
    public function __construct(
        #[MapInputName('Id')]
        public string $id,
        #[MapInputName('Warnings')]
        /** @var array<mixed> */
        public ?array $warnings = null,
    ) {
    }
}
