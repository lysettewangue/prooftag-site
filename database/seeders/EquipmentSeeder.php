<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\EquipmentLifecycle;
use App\Enums\PublicationStatus;
use App\Models\Equipment;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->items() as $item) {
            $equipment = Equipment::query()->updateOrCreate(
                ['sort_order' => $item['sort_order']],
                [
                    'lifecycle' => $item['lifecycle'],
                    'publication_status' => PublicationStatus::Draft,
                ],
            );

            foreach (['fr', 'en'] as $locale) {
                $equipment->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'slug' => $item['slugs'][$locale],
                        'title' => $item['titles'][$locale],
                        'body' => ['html' => $item['bodies'][$locale]],
                    ],
                );
            }
        }
    }

    /**
     * @return list<array{
     *     sort_order: int,
     *     lifecycle: EquipmentLifecycle,
     *     slugs: array{fr: string, en: string},
     *     titles: array{fr: string, en: string},
     *     bodies: array{fr: string, en: string}
     * }>
     */
    private function items(): array
    {
        $rewriteFr = '<p>Équipement du processus de contrôle technique. Brouillon — à compléter avant publication.</p>';
        $rewriteEn = '<p>Technical inspection process equipment. Draft — complete before publishing.</p>';

        return [
            [
                'sort_order' => 1,
                'lifecycle' => EquipmentLifecycle::Current,
                'slugs' => ['fr' => 'banc-de-tests', 'en' => 'test-bench'],
                'titles' => ['fr' => 'Banc de tests', 'en' => 'Test bench'],
                'bodies' => ['fr' => $rewriteFr, 'en' => $rewriteEn],
            ],
            [
                'sort_order' => 2,
                'lifecycle' => EquipmentLifecycle::Current,
                'slugs' => ['fr' => 'tablette-controle-visuel', 'en' => 'visual-inspection-tablet'],
                'titles' => ['fr' => 'Tablette de contrôle visuel', 'en' => 'Visual inspection tablet'],
                'bodies' => ['fr' => $rewriteFr, 'en' => $rewriteEn],
            ],
            [
                'sort_order' => 3,
                'lifecycle' => EquipmentLifecycle::Current,
                'slugs' => ['fr' => 'serveur-de-centre', 'en' => 'centre-server'],
                'titles' => ['fr' => 'Serveur de centre', 'en' => 'Centre server'],
                'bodies' => ['fr' => $rewriteFr, 'en' => $rewriteEn],
            ],
            [
                'sort_order' => 4,
                'lifecycle' => EquipmentLifecycle::Current,
                'slugs' => ['fr' => 'douchette', 'en' => 'barcode-scanner'],
                'titles' => ['fr' => 'Douchette', 'en' => 'Barcode scanner'],
                'bodies' => ['fr' => $rewriteFr, 'en' => $rewriteEn],
            ],
        ];
    }
}
