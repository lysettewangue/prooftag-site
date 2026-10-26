<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\PublicationStatus;
use App\Models\User;
use App\Support\DashboardMetrics;
use App\Support\StaffAccess;
use Filament\Widgets\ChartWidget;

final class PublicationWorkflowChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Cycle éditorial';

    protected ?string $description = 'Répartition des contenus auxquels vous avez accès.';

    protected ?string $maxHeight = '310px';

    protected bool $isCollapsible = true;

    public static function canView(): bool
    {
        $user = auth()->user();

        return $user instanceof User && StaffAccess::isStaff($user);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return [];
        }

        $counts = app(DashboardMetrics::class)->publicationCounts($user);

        return [
            'datasets' => [[
                'label' => 'Contenus',
                'data' => [
                    $counts[PublicationStatus::Draft->value],
                    $counts[PublicationStatus::UnderReview->value],
                    $counts[PublicationStatus::Approved->value],
                    $counts[PublicationStatus::Scheduled->value],
                    $counts[PublicationStatus::Published->value],
                    $counts[PublicationStatus::Archived->value],
                ],
                'backgroundColor' => [
                    '#94A3B8',
                    '#00B8E6',
                    '#008FC7',
                    '#7C6EE6',
                    '#146C43',
                    '#C95037',
                ],
                'borderWidth' => 0,
            ]],
            'labels' => [
                'Brouillons',
                'À relire',
                'Approuvés',
                'Programmés',
                'Publiés',
                'Archivés',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
