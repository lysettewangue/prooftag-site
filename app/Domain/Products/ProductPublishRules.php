<?php

declare(strict_types=1);

namespace App\Domain\Products;

use App\Enums\ProductCode;

final class ProductPublishRules
{
    /**
     * CT-VERIF and DOSER must have an outbound URL before they may enter Published.
     */
    public static function mayEnterPublished(ProductCode $code, ?string $externalUrl): bool
    {
        if (! $code->requiresExternalUrlBeforePublish()) {
            return true;
        }

        return is_string($externalUrl) && $externalUrl !== '';
    }
}
