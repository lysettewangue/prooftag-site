<?php

declare(strict_types=1);

namespace App\Enums;

enum ContentBlockType: string
{
    case RichText = 'rich_text';
    case TextAndMedia = 'text_and_media';
    case Gallery = 'gallery';
    case Timeline = 'timeline';
    case Statistics = 'statistics';
    case Faq = 'faq';
    case DocumentList = 'document_list';
    case Specifications = 'specifications';
    case RelatedSolutions = 'related_solutions';
    case RelatedResources = 'related_resources';
    case Partners = 'partners';
    case Team = 'team';
    case CallToAction = 'call_to_action';

    /**
     * @return list<string>
     */
    public static function codes(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::RichText => 'Texte riche',
            self::TextAndMedia => 'Texte et média',
            self::Gallery => 'Galerie',
            self::Timeline => 'Frise',
            self::Statistics => 'Statistiques',
            self::Faq => 'FAQ',
            self::DocumentList => 'Liste de documents',
            self::Specifications => 'Spécifications',
            self::RelatedSolutions => 'Solutions liées',
            self::RelatedResources => 'Ressources liées',
            self::Partners => 'Partenaires',
            self::Team => 'Équipe',
            self::CallToAction => 'Appel à l’action',
        };
    }
}
