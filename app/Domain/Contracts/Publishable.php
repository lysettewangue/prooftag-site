<?php

declare(strict_types=1);

namespace App\Domain\Contracts;

use DateTimeInterface;

interface Publishable
{
    public function isPubliclyVisible(DateTimeInterface $now): bool;

    public function isPubliclyVisibleIn(string $locale, DateTimeInterface $now): bool;
}
