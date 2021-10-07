<?php

namespace Soyhuce\Docker\DTO;

use Spatie\DataTransferObject\DataTransferObject;

abstract class DockerItem extends DataTransferObject
{
    use FormatKeys;

    public function __construct(array $parameters = [])
    {
        parent::__construct(
            $this->formatKeys($parameters)
        );
    }
}
