<?php

declare(strict_types=1);

namespace App\Actions\Inspection;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Models\SecurityPoint;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

final class ReorderSecurityPoints
{
    /**
     * @param  array<int|string, int|string>  $orderedIds
     */
    public function handle(User $actor, array $orderedIds): void
    {
        $points = SecurityPoint::query()->orderBy('sort_order')->get();

        if ($points->count() !== 12 || count($orderedIds) !== 12) {
            throw new DomainRuleViolation('Reorder must include all twelve security points.');
        }

        $first = $points->first();

        if (! $first instanceof SecurityPoint) {
            throw new DomainRuleViolation('Reorder must include all twelve security points.');
        }

        Gate::forUser($actor)->authorize('update', $first);

        $ids = [];

        foreach ($orderedIds as $id) {
            $ids[] = (int) $id;
        }

        if (count(array_unique($ids)) !== 12) {
            throw new DomainRuleViolation('Reorder ids must be unique.');
        }

        $existing = $points->pluck('id')->map(static fn (mixed $id): int => (int) $id)->sort()->values()->all();
        $given = collect($ids)->sort()->values()->all();

        if ($existing !== $given) {
            throw new DomainRuleViolation('Reorder must include all twelve security points.');
        }

        $desired = [];

        foreach ($ids as $index => $id) {
            $desired[$id] = $index + 1;
        }

        DB::transaction(function () use ($desired): void {
            $current = [];

            foreach (SecurityPoint::query()->get(['id', 'sort_order']) as $row) {
                $current[(int) $row->id] = (int) $row->sort_order;
            }

            foreach ($desired as $startId => $startOrder) {
                if ($current[$startId] === $startOrder) {
                    continue;
                }

                $hole = $current[$startId];
                SecurityPoint::query()->whereKey($startId)->update(['sort_order' => 0]);
                $current[$startId] = 0;

                for ($step = 0; $step < 12; $step++) {
                    $claimant = null;

                    foreach ($desired as $id => $order) {
                        if ($order === $hole && $current[$id] !== $order) {
                            $claimant = $id;
                            break;
                        }
                    }

                    if ($claimant === null) {
                        break;
                    }

                    if ($claimant === $startId) {
                        SecurityPoint::query()->whereKey($startId)->update(['sort_order' => $hole]);
                        $current[$startId] = $hole;
                        break;
                    }

                    $old = $current[$claimant];
                    SecurityPoint::query()->whereKey($claimant)->update(['sort_order' => $hole]);
                    $current[$claimant] = $hole;
                    $hole = $old;
                }
            }
        });

        ContentCache::flush();
    }
}
