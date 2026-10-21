<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $setting = SiteSetting::query()->firstOrNew(['id' => 1]);
        $setting->fill([
            'legal_name' => 'PROOFTAG-CATIS S.A.',
            'brand' => 'PROOFTAG-CATIS · CATIS',
            'building' => "Immeuble Résidence Le Carat, Hôtel de l'Air, Bonapriso",
            'city' => 'Douala',
            'country' => 'Cameroun',
            'postal_box' => 'BP 4601',
            'telephone' => '+237 699 19 29 19',
            'email' => 'info@prooftagcatis.com',
            'canonical_url' => 'https://www.prooftagcatis.com',
            'social_linkedin' => null,
            'social_facebook' => null,
            'social_x' => null,
            'social_youtube' => null,
            'social_instagram' => null,
            'geo_latitude' => null,
            'geo_longitude' => null,
        ]);
        $setting->id = 1;
        $setting->save();

        $setting->translations()->updateOrCreate(
            ['locale' => 'fr'],
            ['tagline' => null],
        );

        $setting->translations()->updateOrCreate(
            ['locale' => 'en'],
            ['tagline' => 'Cameroon Technology Information Services'],
        );
    }
}
