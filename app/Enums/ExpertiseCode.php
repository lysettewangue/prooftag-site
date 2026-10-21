<?php

declare(strict_types=1);

namespace App\Enums;

enum ExpertiseCode: string
{
    case ResearchDevelopment = 'RESEARCH_DEVELOPMENT';
    case TechnicalSupport = 'TECHNICAL_SUPPORT';
    case DataAnalysisReporting = 'DATA_ANALYSIS_REPORTING';
    case DocumentSecurity = 'DOCUMENT_SECURITY';
    case RoadSafetyPrevention = 'ROAD_SAFETY_PREVENTION';

    /**
     * @return list<string>
     */
    public static function codes(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function sortOrder(): int
    {
        return match ($this) {
            self::ResearchDevelopment => 1,
            self::TechnicalSupport => 2,
            self::DataAnalysisReporting => 3,
            self::DocumentSecurity => 4,
            self::RoadSafetyPrevention => 5,
        };
    }

    /**
     * @return array{fr: string, en: string}
     */
    public function slugs(): array
    {
        return match ($this) {
            self::ResearchDevelopment => ['fr' => 'recherche-et-developpement', 'en' => 'research-and-development'],
            self::TechnicalSupport => ['fr' => 'support-technique', 'en' => 'technical-support'],
            self::DataAnalysisReporting => ['fr' => 'analyse-donnees-reporting', 'en' => 'data-analysis-reporting'],
            self::DocumentSecurity => ['fr' => 'securite-documentaire', 'en' => 'document-security'],
            self::RoadSafetyPrevention => ['fr' => 'securite-prevention-routiere', 'en' => 'road-safety-prevention'],
        };
    }

    /**
     * @return array{fr: string, en: string}
     */
    public function titles(): array
    {
        return match ($this) {
            self::ResearchDevelopment => ['fr' => 'Recherche et développement', 'en' => 'Research and development'],
            self::TechnicalSupport => ['fr' => 'Support technique', 'en' => 'Technical support'],
            self::DataAnalysisReporting => ['fr' => 'Analyse de données et reporting', 'en' => 'Data analysis and reporting'],
            self::DocumentSecurity => ['fr' => 'Sécurité documentaire', 'en' => 'Document security'],
            self::RoadSafetyPrevention => ['fr' => 'Sécurité et prévention routière', 'en' => 'Road safety and prevention'],
        };
    }
}
