<?php declare(strict_types=1);

namespace Soyhuce\Docker\Services;

use Soyhuce\Docker\Drivers\Docker;
use Soyhuce\Docker\Drivers\DockerDriver;

abstract class DockerService
{
    public function driver(): DockerDriver
    {
        return app(Docker::class)->driver();
    }
}
