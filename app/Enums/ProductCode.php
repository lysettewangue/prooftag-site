<?php

declare(strict_types=1);

namespace App\Enums;

enum ProductCode: string
{
    case CertidocsCt = 'CERTIDOCS_CT';
    case CertidocsCtReporting = 'CERTIDOCS_CT_REPORTING';
    case CtVerif = 'CT_VERIF';
    case Doser = 'DOSER';
    case VideoEnforcement = 'VIDEO_ENFORCEMENT';

    /**
     * @return list<string>
     */
    public static function codes(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function publicName(): string
    {
        return match ($this) {
            self::CertidocsCt => 'CERTIDOCS CT',
            self::CertidocsCtReporting => 'CERTIDOCS CT Reporting Platform',
            self::CtVerif => 'CT-VERIF',
            self::Doser => 'DOSER',
            self::VideoEnforcement => 'Video Enforcement',
        };
    }

    public function slugFragment(): string
    {
        return match ($this) {
            self::CertidocsCt => 'certidocs-ct',
            self::CertidocsCtReporting => 'certidocs-ct-reporting',
            self::CtVerif => 'ct-verif',
            self::Doser => 'doser',
            self::VideoEnforcement => 'video-enforcement',
        };
    }

    public function sortOrder(): int
    {
        return match ($this) {
            self::CertidocsCt => 1,
            self::CertidocsCtReporting => 2,
            self::CtVerif => 3,
            self::Doser => 4,
            self::VideoEnforcement => 5,
        };
    }
}
