<?php

declare(strict_types=1);

namespace App\Actions\Publication;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\PublicationStatus;
use App\Events\ContentPublished;
use App\Models\Product;
use App\Models\Resource;
use App\Models\User;
use App\Support\ContentCache;
use DateTimeInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

final class TransitionPublication
{
    public function handle(
        Model $model,
        PublicationStatus $to,
        User $actor,
        ?DateTimeInterface $scheduledFor = null,
    ): Model {
        if (! method_exists($model, 'canTransitionTo') || ! $model->canTransitionTo($to)) {
            $from = $model->getAttribute('publication_status');
            $fromValue = $from instanceof PublicationStatus ? $from->value : (string) $from;

            throw new DomainRuleViolation("Cannot transition from {$fromValue} to {$to->value}.");
        }

        $this->authorize($model, $to, $actor);

        if ($to === PublicationStatus::Published && $model instanceof Product && ! $model->mayEnterPublished()) {
            throw new DomainRuleViolation('CT-VERIF and DOSER require an external URL before publish.');
        }

        if ($to === PublicationStatus::Published && $model instanceof Resource && ! $model->mayEnterPublished()) {
            throw new DomainRuleViolation('A document file is required before publish.');
        }

        $now = now();

        if ($to === PublicationStatus::Scheduled) {
            if ($scheduledFor === null || $scheduledFor->getTimestamp() <= $now->getTimestamp()) {
                throw new DomainRuleViolation('Schedule time must be in the future.');
            }

            $model->forceFill([
                'publication_status' => $to,
                'scheduled_for' => $scheduledFor,
                'published_at' => null,
            ])->save();

            ContentCache::flush();

            return $model;
        }

        $model->forceFill([
            'publication_status' => $to,
            'scheduled_for' => null,
            'published_at' => $to === PublicationStatus::Published ? $now : $model->getAttribute('published_at'),
        ])->save();

        if ($to === PublicationStatus::Published) {
            ContentPublished::dispatch($model, $actor);
        } else {
            ContentCache::flush();
        }

        return $model;
    }

    private function authorize(Model $model, PublicationStatus $to, User $actor): void
    {
        if ($to === PublicationStatus::Approved) {
            if (Gate::forUser($actor)->denies('update', $model) && Gate::forUser($actor)->denies('publish', $model)) {
                throw new AuthorizationException;
            }

            return;
        }

        $ability = $to->requiresPublishPermission() ? 'publish' : 'update';

        Gate::forUser($actor)->authorize($ability, $model);
    }
}
