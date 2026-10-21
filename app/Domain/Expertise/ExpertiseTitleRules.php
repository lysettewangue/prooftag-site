<?php

declare(strict_types=1);

namespace App\Domain\Expertise;

use App\Enums\ProductCode;

final class ExpertiseTitleRules
{
    public static function mayUseTitle(string $title): bool
    {
        $normalized = mb_strtolower(trim($title));

        foreach (ProductCode::cases() as $code) {
            if ($normalized === mb_strtolower($code->publicName())) {
                return false;
            }
        }

        return true;
    }
}
