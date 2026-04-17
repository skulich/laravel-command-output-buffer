<?php

declare(strict_types=1);

namespace SKulich\LaravelCommandOutputBuffer\Tests;

use Illuminate\Contracts\Console\Kernel;
use Orchestra\Testbench\TestCase as BaseTestCase;
use SKulich\LaravelCommandOutputBuffer\Tests\Fixtures\BufferedCommand;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app?->make(Kernel::class)
            ->registerCommand(new BufferedCommand);
    }
}
