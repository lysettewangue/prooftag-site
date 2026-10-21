<?php

declare(strict_types=1);

namespace App\Domain\Contracts;

interface Translatable
{
    public function hasLocale(string $locale): bool;

    public function translationFor(string $locale): mixed;
}
