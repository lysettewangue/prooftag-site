<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PublicationStatus;
use App\Enums\ResourceRegulatoryStatus;
use App\Models\Resource;
use Illuminate\Database\Seeder;

class LibraryDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $resource = Resource::query()->updateOrCreate(
            ['regulatory_status' => ResourceRegulatoryStatus::Historical->value, 'relates_to_ct' => true],
            [
                'authority' => null,
                'issued_on' => '2020-03-03',
                'publication_status' => PublicationStatus::Draft,
            ],
        );

        $resource->translations()->updateOrCreate(
            ['locale' => 'fr'],
            [
                'slug' => 'reforme-vignettes-2020',
                'title' => 'Réforme des vignettes 2020 (historique)',
                'summary' => 'Document de travail VER-08 : current vs historical. Pas de PDF attaché — ne peut pas passer en Published.',
            ],
        );

        $resource->translations()->updateOrCreate(
            ['locale' => 'en'],
            [
                'slug' => '2020-vignette-reform',
                'title' => '2020 vignette reform (historical)',
                'summary' => 'VER-08 working record: current vs historical. No attached PDF — cannot enter Published.',
            ],
        );
    }
}
