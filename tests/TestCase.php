<?php declare(strict_types=1);

namespace Test;

use ErrorException;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDeprecationHandling;
use Soyhuce\Docker\ServiceProvider;
use Spatie\LaravelData\LaravelDataServiceProvider;
use function in_array;

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

    protected function withoutDeprecationHandling(): static
    {
        if ($this->originalDeprecationHandler == null) {
            $this->originalDeprecationHandler = set_error_handler(function (
                $level,
                $message,
                $file = '',
                $line = 0,
            ): void {
                if (in_array($level, [E_DEPRECATED, E_USER_DEPRECATED], true) || (error_reporting() & $level)) {
                    // Silenced vendor errors
                    if (str_starts_with($file, realpath(__DIR__ . '/../vendor/guzzlehttp/promises'))) {
                        return;
                    }
                    if (str_starts_with($file, realpath(__DIR__ . '/../vendor/guzzlehttp/psr7'))) {
                        return;
                    }

                    throw new ErrorException($message, 0, $level, $file, $line);
                }
            });
        }

        return $this;
    }
}
