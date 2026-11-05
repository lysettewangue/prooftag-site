<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\ExpertiseCode;
use App\Models\Expertise;

/**
 * Static premium visuals for public expertise surfaces (hub + detail).
 */
final class ExpertiseVisual
{
    public static function hubImage(): string
    {
        return asset('images/content/expertise/hub.jpg');
    }

    public static function imageFor(Expertise|ExpertiseCode|string $expertise): string
    {
        $code = self::code($expertise);

        $file = match ($code) {
            ExpertiseCode::ResearchDevelopment->value => 'research-development.jpg',
            ExpertiseCode::TechnicalSupport->value => 'technical-support.jpg',
            ExpertiseCode::DataAnalysisReporting->value => 'data-analysis-reporting.jpg',
            ExpertiseCode::DocumentSecurity->value => 'document-security.jpg',
            ExpertiseCode::RoadSafetyPrevention->value => 'road-safety-prevention.jpg',
            default => 'hub.jpg',
        };

        return asset('images/content/expertise/'.$file);
    }

    public static function iconFor(Expertise|ExpertiseCode|string $expertise): string
    {
        $code = self::code($expertise);

        return match ($code) {
            ExpertiseCode::ResearchDevelopment->value => 'cpu',
            ExpertiseCode::TechnicalSupport->value => 'clipboard-check',
            ExpertiseCode::DataAnalysisReporting->value => 'database',
            ExpertiseCode::DocumentSecurity->value => 'shield-check',
            ExpertiseCode::RoadSafetyPrevention->value => 'route',
            default => 'badge-check',
        };
    }

    private static function code(Expertise|ExpertiseCode|string $expertise): string
    {
        if ($expertise instanceof Expertise) {
            return $expertise->code->value;
        }

        if ($expertise instanceof ExpertiseCode) {
            return $expertise->value;
        }

        return $expertise;
    }
}
