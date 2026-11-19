<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Str;

final class CameroonCityCoordinates
{
    /** @var array<string, array{latitude: float, longitude: float}> */
    private const COORDINATES = [
        'bafoussam' => ['latitude' => 5.4778, 'longitude' => 10.4176],
        'bamenda' => ['latitude' => 5.9631, 'longitude' => 10.1591],
        'bertoua' => ['latitude' => 4.5773, 'longitude' => 13.6846],
        'buea' => ['latitude' => 4.1550, 'longitude' => 9.2410],
        'douala' => ['latitude' => 4.0511, 'longitude' => 9.7679],
        'douala bassa' => ['latitude' => 4.0714, 'longitude' => 9.7867],
        'douala bonaberi' => ['latitude' => 4.0832, 'longitude' => 9.6756],
        'douala yassa' => ['latitude' => 3.9978, 'longitude' => 9.8428],
        'dschang' => ['latitude' => 5.4456, 'longitude' => 10.0491],
        'edea' => ['latitude' => 3.8000, 'longitude' => 10.1333],
        'ebolowa' => ['latitude' => 2.9000, 'longitude' => 11.1500],
        'foumbot' => ['latitude' => 5.5070, 'longitude' => 10.6325],
        'garoua' => ['latitude' => 9.3019, 'longitude' => 13.3977],
        'guider' => ['latitude' => 9.9342, 'longitude' => 13.9486],
        'kousseri' => ['latitude' => 12.0777, 'longitude' => 15.0306],
        'kribi' => ['latitude' => 2.9373, 'longitude' => 9.9077],
        'kumba' => ['latitude' => 4.6363, 'longitude' => 9.4469],
        'limbe' => ['latitude' => 4.0186, 'longitude' => 9.2053],
        'maroua' => ['latitude' => 10.5910, 'longitude' => 14.3158],
        'mbouda' => ['latitude' => 5.6258, 'longitude' => 10.2542],
        'ngaoundere' => ['latitude' => 7.3277, 'longitude' => 13.5847],
        'nkongsamba' => ['latitude' => 4.9547, 'longitude' => 9.9404],
        'ombe' => ['latitude' => 4.0154, 'longitude' => 9.2238],
        'bandjoun' => ['latitude' => 5.3763, 'longitude' => 10.4138],
        'yassa' => ['latitude' => 3.9978, 'longitude' => 9.8428],
        'yaounde' => ['latitude' => 3.8480, 'longitude' => 11.5021],
    ];

    /** @return array{latitude: float, longitude: float}|null */
    public static function forCity(?string $city): ?array
    {
        $normalized = trim(Str::lower(Str::ascii((string) $city)));

        if ($normalized === '') {
            return null;
        }

        if (isset(self::COORDINATES[$normalized])) {
            return self::COORDINATES[$normalized];
        }

        foreach (self::COORDINATES as $name => $coordinates) {
            if (str_contains($normalized, $name)) {
                return $coordinates;
            }
        }

        return null;
    }
}
