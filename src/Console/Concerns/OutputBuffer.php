<?php

declare(strict_types=1);

namespace SKulich\LaravelCommandOutputBuffer\Console\Concerns;

trait OutputBuffer
{
    private array $buffer = [];

    private bool $buffered = false;

    private bool $v = false;

    private bool $vv = false;

    private bool $vvv = false;

    protected function obStart(?\SplStack &$context = null): void
    {
        $this->v = $this->output->isVerbose();
        $this->vv = $this->output->isVeryVerbose();
        $this->vvv = $this->output->isDebug();

        $this->buffered = true;

        if (func_num_args() > 0 && ! $context instanceof \SplStack) {
            $context = new class extends \SplStack
            {
                public function __destruct()
                {
                    \call_user_func($this->pop());
                }
            };

            $context->push(fn () => $this->obFlush());
        }
    }

    protected function obPause(): void
    {
        $this->buffered = false;
    }

    protected function obStop(): void
    {
        $this->buffer = [];
        $this->buffered = false;
    }

    protected function obClean(): void
    {
        $this->buffer = [];
    }

    protected function obFlush(): void
    {
        while ($line = array_shift($this->buffer)) {
            parent::line(...$line);
        }
    }

    /**
     * @param  string  $string
     * @param  string|null  $style
     * @param  int|string|null  $verbosity
     */
    public function line($string, $style = null, $verbosity = null): void
    {
        if (! $this->buffered) {
            parent::line($string, $style, $verbosity);

            return;
        }

        $this->buffer[] = [$string, $style, $verbosity];
    }

    /**
     * Write a blank line.
     *
     * @param  int  $count
     * @return $this
     */
    public function newLine($count = 1): static
    {
        for ($i = 0; $i < $count; $i++) {
            $this->line('');
        }

        return $this;
    }

    public function v(string $string, ?string $style = null): void
    {
        if ($this->v) {
            $this->line($string, $style, 'v');
        }
    }

    public function vv(string $string, ?string $style = null): void
    {
        if ($this->vv) {
            $this->line($string, $style, 'vv');
        }
    }

    public function vvv(string $string, ?string $style = null): void
    {
        if ($this->vvv) {
            $this->line($string, $style, 'vvv');
        }
    }
}
