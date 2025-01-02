<?php declare(strict_types=1);

namespace Soyhuce\Docker\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class ContainerCreateItem extends Data
{
    /**
     * @param array<int, string>|null $warnings
     */
    public function __construct(
        #[MapInputName('Id')]
        public string $id,
        #[MapInputName('Warnings')]
        public ?array $warnings = null,
    ) {
    }
}
