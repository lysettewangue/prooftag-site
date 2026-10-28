<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\ContactRequest;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

final class ContactRequestsChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Demandes sur 7 jours';

    protected ?string $description = 'Volume reçu jour par jour.';

    protected int | string | array $columnSpan = 'full';

    protected ?string $maxHeight = '280px';

    protected function getType(): string
    {
        return 'line';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $labels = [];
        $values = [];

        for ($offset = 6; $offset >= 0; $offset--) {
            $day = Carbon::today()->subDays($offset);
            $labels[] = $day->translatedFormat('D j');
            $values[] = ContactRequest::query()
                ->whereDate('created_at', $day)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Demandes',
                    'data' => $values,
                    'borderColor' => '#00AEFE',
                    'backgroundColor' => 'rgba(0, 174, 254, 0.18)',
                    'fill' => true,
                    'tension' => 0.35,
                    'pointBackgroundColor' => '#026AA2',
                    'pointBorderColor' => '#FFFFFF',
                ],
            ],
            'labels' => $labels,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                    'grid' => [
                        'color' => 'rgba(2, 106, 162, 0.08)',
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }
}
