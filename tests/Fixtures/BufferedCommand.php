<?php

declare(strict_types=1);

namespace SKulich\LaravelCommandOutputBuffer\Tests\Fixtures;

use Illuminate\Console\Command;
use SKulich\LaravelCommandOutputBuffer\Console\Concerns\OutputBuffer;

final class BufferedCommand extends Command
{
    use OutputBuffer;

    protected $signature = 'test:buffer {scenario}';

    public function handle(): int
    {
        $method = 'scenario'.ucfirst((string) $this->argument('scenario'));
        $this->$method();

        return self::SUCCESS;
    }

    public function scenarioDirect(): void
    {
        $this->line('direct-output');
    }

    public function scenarioFlush(): void
    {
        $this->obStart();
        $this->line('buffered-line-1');
        $this->line('buffered-line-2');
        $this->obFlush();
    }

    public function scenarioClean(): void
    {
        $this->obStart();
        $this->line('should-not-appear');
        $this->obClean();
        $this->obFlush();
    }

    public function scenarioStop(): void
    {
        $this->obStart();
        $this->line('should-not-appear');
        $this->obStop();
        $this->line('after-stop');
    }

    public function scenarioPause(): void
    {
        $this->obStart();
        $this->line('buffered');
        $this->obPause();
        $this->line('direct-during-pause');
        $this->obStart();
        $this->line('buffered-again');
        $this->obFlush();
    }

    public function scenarioVerbose(): void
    {
        $this->obStart();
        $this->v('verbose-line');
        $this->obFlush();
    }

    public function scenarioVeryVerbose(): void
    {
        $this->obStart();
        $this->vv('very-verbose-line');
        $this->obFlush();
    }

    public function scenarioDebug(): void
    {
        $this->obStart();
        $this->vvv('debug-line');
        $this->obFlush();
    }

    public function scenarioStyled(): void
    {
        $this->obStart();
        $this->line('styled-line', 'info');
        $this->obFlush();
    }

    public function scenarioNewline(): void
    {
        $this->obStart();
        $this->line('before');
        $this->newLine(2);
        $this->line('after');
        $this->obFlush();
    }

    public function scenarioNewlineClean(): void
    {
        $this->obStart();
        $this->newLine();
        $this->obClean();
        $this->obFlush();
    }

    public function scenarioDeferred(): void
    {
        $this->obStart($defer);
        $this->line('deferred-line-1');
        $this->line('deferred-line-2');
    }

    public function scenarioDeferredClean(): void
    {
        $this->obStart($defer);
        $this->line('should-not-appear');
        $this->obClean();
    }
}
