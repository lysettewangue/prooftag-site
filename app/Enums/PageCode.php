<?php

declare(strict_types=1);

namespace App\Enums;

enum PageCode: string
{
    case Home = 'home';
    case About = 'about';
    case Organisation = 'organisation';
    case History = 'history';
    case Team = 'team';
    case Partners = 'partners';
    case Careers = 'careers';
    case Contact = 'contact';
    case LegalNotice = 'legal_notice';
    case Privacy = 'privacy';
    case TechnicalInspection = 'technical_inspection';

    /**
     * @return list<string>
     */
    public static function codes(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return array{fr: string, en: string}
     */
    public function slugs(): array
    {
        return match ($this) {
            self::Home => ['fr' => '', 'en' => ''],
            self::About => ['fr' => 'a-propos', 'en' => 'about'],
            self::Organisation => ['fr' => 'a-propos/organisation', 'en' => 'about/organisation'],
            self::History => ['fr' => 'a-propos/historique', 'en' => 'about/history'],
            self::Team => ['fr' => 'a-propos/equipe', 'en' => 'about/team'],
            self::Partners => ['fr' => 'a-propos/partenaires', 'en' => 'about/partners'],
            self::Careers => ['fr' => 'a-propos/carrieres', 'en' => 'about/careers'],
            self::Contact => ['fr' => 'contact', 'en' => 'contact'],
            self::LegalNotice => ['fr' => 'mentions-legales', 'en' => 'legal-notice'],
            self::Privacy => ['fr' => 'politique-de-confidentialite', 'en' => 'privacy-policy'],
            self::TechnicalInspection => ['fr' => 'controle-technique', 'en' => 'technical-inspection'],
        };
    }

    /**
     * @return array{fr: string, en: string}
     */
    public function titles(): array
    {
        return match ($this) {
            self::Home => ['fr' => 'Accueil', 'en' => 'Home'],
            self::About => ['fr' => 'À propos', 'en' => 'About'],
            self::Organisation => ['fr' => 'Organisation', 'en' => 'Organisation'],
            self::History => ['fr' => 'Historique', 'en' => 'History'],
            self::Team => ['fr' => 'Équipe', 'en' => 'Team'],
            self::Partners => ['fr' => 'Partenaires', 'en' => 'Partners'],
            self::Careers => ['fr' => 'Carrières', 'en' => 'Careers'],
            self::Contact => ['fr' => 'Contact', 'en' => 'Contact'],
            self::LegalNotice => ['fr' => 'Mentions légales', 'en' => 'Legal notice'],
            self::Privacy => ['fr' => 'Politique de confidentialité', 'en' => 'Privacy policy'],
            self::TechnicalInspection => ['fr' => 'Contrôle technique', 'en' => 'Technical inspection'],
        };
    }
}
