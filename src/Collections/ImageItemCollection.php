<?php

namespace Soyhuce\Docker\Collections;

use Soyhuce\Docker\DTO\ImageItem;
use Spatie\DataTransferObject\DataTransferObjectCollection;

class ImageItemCollection extends DataTransferObjectCollection
{
    public function current(): ImageItem
    {
        return parent::current();
    }

    public static function fromResponse(array $items): self
    {
        return new self(array_map(static fn ($item) => ImageItem::fromResponse($item), $items));
    }
}
