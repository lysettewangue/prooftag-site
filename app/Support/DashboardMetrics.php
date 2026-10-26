<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\PublicationStatus;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

final class DashboardMetrics
{
    /**
     * @return array<string, int>
     */
    public function publicationCounts(User $user): array
    {
        $counts = array_fill_keys(PublicationStatus::codes(), 0);

        foreach (PublishableTypes::classes() as $model) {
            if (Gate::forUser($user)->denies('viewAny', $model)) {
                continue;
            }

            $rows = $model::query()
                ->selectRaw('publication_status, COUNT(*) as aggregate')
                ->groupBy('publication_status')
                ->pluck('aggregate', 'publication_status');

            foreach ($rows as $status => $count) {
                if (array_key_exists((string) $status, $counts)) {
                    $counts[(string) $status] += (int) $count;
                }
            }
        }

        return $counts;
    }
}
