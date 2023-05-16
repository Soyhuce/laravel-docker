<?php

namespace Test;

use Illuminate\Foundation\Testing\Concerns\InteractsWithDeprecationHandling;
use Soyhuce\Docker\ServiceProvider;
use Spatie\LaravelData\LaravelDataServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use InteractsWithDeprecationHandling;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutDeprecationHandling();
    }

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
            LaravelDataServiceProvider::class,
        ];
    }
}
