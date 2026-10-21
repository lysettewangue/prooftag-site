<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Domain\Publication\Visibility;
use App\Domain\Translation\LocaleAvailability;
use App\Enums\PublicationStatus;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property PublicationStatus $publication_status
 * @property Carbon|null $published_at
 * @property Carbon|null $scheduled_for
 *
 * @method bool hasLocale(string $locale)
 */
trait Publishable
{
    use SoftDeletes;

    public function initializePublishable(): void
    {
        $this->mergeCasts([
            'publication_status' => PublicationStatus::class,
            'published_at' => 'datetime',
            'scheduled_for' => 'datetime',
        ]);
    }

    public function isPubliclyVisible(DateTimeInterface $now): bool
    {
        return Visibility::isPubliclyVisible($this->publication_status, $this->published_at, $now);
    }

    public function isPubliclyVisibleIn(string $locale, DateTimeInterface $now): bool
    {
        return LocaleAvailability::hasPublicLocale(
            $this->isPubliclyVisible($now),
            $this->hasLocale($locale),
        );
    }

    public function canTransitionTo(PublicationStatus $to): bool
    {
        return $this->publication_status->canTransitionTo($to);
    }

    /**
     * @param  Builder<covariant Model>  $query
     */
    public function scopePubliclyVisible(Builder $query, DateTimeInterface $now): void
    {
        $query
            ->where('publication_status', PublicationStatus::Published)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', $now);
    }
}
