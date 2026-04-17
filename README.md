# Laravel Command Output Buffer

[![Latest Version on Packagist](https://img.shields.io/packagist/v/skulich/laravel-command-output-buffer.svg)](https://packagist.org/packages/skulich/laravel-command-output-buffer)
![PHP Version Require](https://img.shields.io/packagist/php-v/skulich/laravel-command-output-buffer)
![Laravel Version](https://img.shields.io/badge/laravel-%5E12.0%20%7C%7C%20%5E13.0-red?logo=laravel)
[![Run Tests](https://github.com/skulich/laravel-command-output-buffer/actions/workflows/tests.yml/badge.svg)](https://github.com/skulich/laravel-command-output-buffer/actions)
![Code Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen)
![License](https://img.shields.io/packagist/l/skulich/laravel-command-output-buffer.svg)
![Total Downloads](https://img.shields.io/packagist/dt/skulich/laravel-command-output-buffer.svg)

A trait for buffering Artisan Command Output in Laravel. Inspired by PHP's `ob_start()` / `ob_flush()`.

**Use cases:**

- **Conditional output** — accumulate output during command execution, then decide to show it (flush) or discard it (clean) based on the result
- **Deferred output** — collect output and display it all at once at the right moment

## Installation

```shell
composer require skulich/laravel-command-output-buffer
```

## Usage

Add the `OutputBuffer` trait to your Artisan command:

```php
use Illuminate\Console\Command;
use SKulich\LaravelCommandOutputBuffer\Console\Concerns\OutputBuffer;

class ProcessDataCommand extends Command
{
    use OutputBuffer;

    protected $signature = 'app:process-data';

    public function handle(): int
    {
        $this->obStart();

        $this->line('Processing item 1...');
        $this->line('Processing item 2...');
        $this->v('Verbose detail');

        if ($this->hasErrors()) {
            $this->obFlush();  // show accumulated output — something went wrong

            return self::FAILURE;
        }

        $this->obClean();  // discard output — all ok

        return self::SUCCESS;
    }
}
```

### Deferred output

Pass a variable to `obStart()` to auto-flush when it goes out of scope:

```php
public function handle(): int
{
    $this->obStart($defer);

    $this->info('Step 1 done');
    $this->info('Step 2 done');

    return self::SUCCESS;
    // buffer is automatically flushed here when $defer is destroyed
}
```

## API

All `ob*` methods are `protected` — they are meant to be called from within the command itself.

| Method | Description |
|--------|-------------|
| `obStart()` | Start buffering output |
| `obStart(&$defer)` | Start buffering with auto-flush on scope exit |
| `obPause()` | Pause buffering — output goes directly to console |
| `obStop()` | Stop buffering and clear the buffer |
| `obClean()` | Clear the buffer without stopping |
| `obFlush()` | Output all buffered lines to the console |
| `v($string, $style)` | Buffer a line only when `-v` (verbose) |
| `vv($string, $style)` | Buffer a line only when `-vv` (very verbose) |
| `vvv($string, $style)` | Buffer a line only when `-vvv` (debug) |

The `line()` method is automatically intercepted — when buffering is active, output goes to the buffer instead of the console.

## Tests

Run the entire test suite:

```shell
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for more information.

## License

The MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.
