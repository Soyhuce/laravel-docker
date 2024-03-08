<?php declare(strict_types=1);

namespace Soyhuce\Docker\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class ImageItem extends Data
{
    public function __construct(
        #[MapInputName('Id')]
        public string $id,
        #[MapInputName('Created')]
        #[WithCast(DateTimeInterfaceCast::class, format: 'U')]
        public Carbon $created,
        #[MapInputName('RepoTags')]
        public array $repoTags,
        #[MapInputName('Size')]
        public int $size,
    ) {
    }
}
