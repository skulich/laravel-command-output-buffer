<?php

declare(strict_types=1);

// Task 4: Direct output and flush

it('outputs directly when buffer is not started', function (): void {
    $this->artisan('test:buffer', ['scenario' => 'direct'])
        ->expectsOutput('direct-output')
        ->assertSuccessful();
});

it('buffers output and flushes it', function (): void {
    $this->artisan('test:buffer', ['scenario' => 'flush'])
        ->expectsOutput('buffered-line-1')
        ->expectsOutput('buffered-line-2')
        ->assertSuccessful();
});

// Task 5: Clean, stop, pause

it('cleans the buffer without outputting', function (): void {
    $this->artisan('test:buffer', ['scenario' => 'clean'])
        ->doesntExpectOutput('should-not-appear')
        ->assertSuccessful();
});

it('stops buffering and clears the buffer', function (): void {
    $this->artisan('test:buffer', ['scenario' => 'stop'])
        ->doesntExpectOutput('should-not-appear')
        ->expectsOutput('after-stop')
        ->assertSuccessful();
});

it('pauses and resumes buffering', function (): void {
    $this->artisan('test:buffer', ['scenario' => 'pause'])
        ->expectsOutput('direct-during-pause')
        ->expectsOutput('buffered')
        ->expectsOutput('buffered-again')
        ->assertSuccessful();
});

// Task 6: Verbosity helpers

it('buffers verbose output when verbose mode is on', function (): void {
    $this->artisan('test:buffer', ['scenario' => 'verbose', '--verbose' => true])
        ->expectsOutput('verbose-line')
        ->assertSuccessful();
});

it('ignores verbose output when verbose mode is off', function (): void {
    $this->artisan('test:buffer', ['scenario' => 'verbose'])
        ->doesntExpectOutput('verbose-line')
        ->assertSuccessful();
});

it('buffers very verbose output when very verbose mode is on', function (): void {
    $this->artisan('test:buffer', ['scenario' => 'veryVerbose', '-vv' => true])
        ->expectsOutput('very-verbose-line')
        ->assertSuccessful();
});

it('ignores very verbose output when very verbose mode is off', function (): void {
    $this->artisan('test:buffer', ['scenario' => 'veryVerbose'])
        ->doesntExpectOutput('very-verbose-line')
        ->assertSuccessful();
});

it('buffers debug output when debug mode is on', function (): void {
    $this->artisan('test:buffer', ['scenario' => 'debug', '-vvv' => true])
        ->expectsOutput('debug-line')
        ->assertSuccessful();
});

it('ignores debug output when debug mode is off', function (): void {
    $this->artisan('test:buffer', ['scenario' => 'debug'])
        ->doesntExpectOutput('debug-line')
        ->assertSuccessful();
});

// Task 7: Styled output

it('preserves style when flushing', function (): void {
    $this->artisan('test:buffer', ['scenario' => 'styled'])
        ->expectsOutput('styled-line')
        ->assertSuccessful();
});

// newLine

it('buffers newLine calls and flushes them', function (): void {
    $this->artisan('test:buffer', ['scenario' => 'newline'])
        ->expectsOutput('before')
        ->expectsOutput('after')
        ->assertSuccessful();
});

it('cleans buffered newLine calls', function (): void {
    $this->artisan('test:buffer', ['scenario' => 'newlineClean'])
        ->assertSuccessful();
});

// Deferred output

it('auto-flushes buffer on command completion', function (): void {
    $this->artisan('test:buffer', ['scenario' => 'deferred'])
        ->expectsOutput('deferred-line-1')
        ->expectsOutput('deferred-line-2')
        ->assertSuccessful();
});

it('auto-flush outputs nothing when buffer was cleaned', function (): void {
    $this->artisan('test:buffer', ['scenario' => 'deferredClean'])
        ->doesntExpectOutput('should-not-appear')
        ->assertSuccessful();
});
