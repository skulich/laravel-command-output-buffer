<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();

arch()->expect(['die', 'dd', 'dump'])->not->toBeUsed();

arch()
    ->expect('SKulich\LaravelCommandOutputBuffer')
    ->toUseStrictTypes()
    ->toUseStrictEquality();
