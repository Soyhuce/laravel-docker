<?php

namespace Soyhuce\Docker\DTO;

class ContainerCreateItem extends DockerItem
{
    public string $id;

    /** @var array<mixed> */
    public ?array $warnings = null;
}
