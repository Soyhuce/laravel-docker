<?php

namespace Soyhuce\Docker\DTO;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

abstract class DockerItem extends FlexibleDataTransferObject
{
    use FormatKeys;

    public function __construct(array $parameters = [])
    {
        parent::__construct(
            $this->formatKeys($parameters)
        );
    }
}
