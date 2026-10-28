<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\CentreVerificationStatus;
use App\Enums\ContactRequestStatus;
use App\Enums\PublicationStatus;
use App\Filament\Resources\ContactRequests\ContactRequestResource;
use App\Filament\Resources\InspectionCentres\InspectionCentreResource;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Products\ProductResource;
use App\Models\ContactRequest;
use App\Models\InspectionCentre;
use App\Models\Post;
use App\Models\Product;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class CmsStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Vue d’ensemble';

    protected ?string $description = 'Indicateurs clés du contenu et du support.';

    /**
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        $productsPublished = Product::query()
            ->where('publication_status', PublicationStatus::Published)
            ->count();
        $postsPublished = Post::query()
            ->where('publication_status', PublicationStatus::Published)
            ->count();
        $centresActive = InspectionCentre::query()
            ->where('verification_status', CentreVerificationStatus::Active)
            ->count();
        $contactsNew = ContactRequest::query()
            ->where('status', ContactRequestStatus::New)
            ->count();

        return [
            Stat::make('Produits publiés', (string) $productsPublished)
                ->description('Catalogue public')
                ->descriptionIcon(Heroicon::OutlinedCube)
                ->icon(Heroicon::OutlinedCube)
                ->color('primary')
                ->url(ProductResource::getUrl('index')),
            Stat::make('Articles publiés', (string) $postsPublished)
                ->description('Actualités')
                ->descriptionIcon(Heroicon::OutlinedNewspaper)
                ->icon(Heroicon::OutlinedNewspaper)
                ->color('primary')
                ->url(PostResource::getUrl('index')),
            Stat::make('Centres actifs', (string) $centresActive)
                ->description('Contrôle technique')
                ->descriptionIcon(Heroicon::OutlinedMapPin)
                ->icon(Heroicon::OutlinedMapPin)
                ->color('success')
                ->url(InspectionCentreResource::getUrl('index')),
            Stat::make('Demandes nouvelles', (string) $contactsNew)
                ->description('À traiter')
                ->descriptionIcon(Heroicon::OutlinedInbox)
                ->icon(Heroicon::OutlinedInbox)
                ->color($contactsNew > 0 ? 'danger' : 'success')
                ->url(ContactRequestResource::getUrl('index')),
        ];
    }
}
