<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\CentreVerificationStatus;
use App\Enums\ContactRequestStatus;
use App\Enums\PublicationStatus;
use App\Enums\StaffPermission;
use App\Models\ContactRequest;
use App\Models\InspectionCentre;
use App\Models\User;
use App\Support\DashboardMetrics;
use App\Support\StaffAccess;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class OperationsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected function getHeading(): string
    {
        return 'À retenir aujourd’hui';
    }

    /**
     * @return array<int, Stat>
     */
    protected function getStats(): array
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return [];
        }

        $counts = app(DashboardMetrics::class)->publicationCounts($user);
        $stats = [
            Stat::make('À relire', $counts[PublicationStatus::UnderReview->value])
                ->description('Contenus en attente de décision')
                ->descriptionIcon('heroicon-m-eye')
                ->color('info'),
            Stat::make('Programmés', $counts[PublicationStatus::Scheduled->value])
                ->description('Publications planifiées')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),
            Stat::make('En ligne', $counts[PublicationStatus::Published->value])
                ->description('Contenus visibles')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];

        if (StaffAccess::can($user, StaffPermission::VerifyCentre)) {
            $stats[] = Stat::make(
                'Centres à vérifier',
                InspectionCentre::query()->where('verification_status', CentreVerificationStatus::Unverified)->count(),
            )
                ->description('Fiches non encore validées')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('warning');
        }

        if (StaffAccess::can($user, StaffPermission::ContactInbox)) {
            $stats[] = Stat::make(
                'Nouvelles demandes',
                ContactRequest::query()->where('status', ContactRequestStatus::New)->count(),
            )
                ->description('Messages à prendre en charge')
                ->descriptionIcon('heroicon-m-inbox')
                ->color('danger');
        }

        return $stats;
    }
}
