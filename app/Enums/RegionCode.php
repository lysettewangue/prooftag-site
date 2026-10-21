<?php

declare(strict_types=1);

namespace App\Enums;

enum RegionCode: string
{
    case Adamaoua = 'AD';
    case Centre = 'CE';
    case Est = 'ES';
    case ExtremeNord = 'EN';
    case Littoral = 'LT';
    case Nord = 'NO';
    case NordOuest = 'NW';
    case Ouest = 'OU';
    case Sud = 'SU';
    case SudOuest = 'SW';

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
    public function names(): array
    {
        return match ($this) {
            self::Adamaoua => ['fr' => 'Adamaoua', 'en' => 'Adamawa'],
            self::Centre => ['fr' => 'Centre', 'en' => 'Centre'],
            self::Est => ['fr' => 'Est', 'en' => 'East'],
            self::ExtremeNord => ['fr' => 'Extrême-Nord', 'en' => 'Far North'],
            self::Littoral => ['fr' => 'Littoral', 'en' => 'Littoral'],
            self::Nord => ['fr' => 'Nord', 'en' => 'North'],
            self::NordOuest => ['fr' => 'Nord-Ouest', 'en' => 'North-West'],
            self::Ouest => ['fr' => 'Ouest', 'en' => 'West'],
            self::Sud => ['fr' => 'Sud', 'en' => 'South'],
            self::SudOuest => ['fr' => 'Sud-Ouest', 'en' => 'South-West'],
        };
    }

    /**
     * @return array{fr: string, en: string}
     */
    public function slugs(): array
    {
        return match ($this) {
            self::Adamaoua => ['fr' => 'adamaoua', 'en' => 'adamawa'],
            self::Centre => ['fr' => 'centre', 'en' => 'centre'],
            self::Est => ['fr' => 'est', 'en' => 'east'],
            self::ExtremeNord => ['fr' => 'extreme-nord', 'en' => 'far-north'],
            self::Littoral => ['fr' => 'littoral', 'en' => 'littoral'],
            self::Nord => ['fr' => 'nord', 'en' => 'north'],
            self::NordOuest => ['fr' => 'nord-ouest', 'en' => 'north-west'],
            self::Ouest => ['fr' => 'ouest', 'en' => 'west'],
            self::Sud => ['fr' => 'sud', 'en' => 'south'],
            self::SudOuest => ['fr' => 'sud-ouest', 'en' => 'south-west'],
        };
    }
}
