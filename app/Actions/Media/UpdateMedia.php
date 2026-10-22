<?php

declare(strict_types=1);

namespace App\Actions\Media;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Domain\Media\MediaRules;
use App\Models\Media;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

final class UpdateMedia
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Media $media, User $actor, array $data): Media
    {
        Gate::forUser($actor)->authorize('update', $media);

        $altFr = array_key_exists('alt.fr', $data)
            ? trim((string) $data['alt.fr'])
            : $media->altFr();
        $rights = array_key_exists('rights', $data)
            ? trim((string) $data['rights'])
            : $media->rights();

        $this->assertLength('alt.fr', $altFr);
        $this->assertLength('rights', $rights);

        $altEn = $media->altEn();

        if (MediaRules::isImage((string) $media->mime_type) && ($altFr === '' || $altEn === '')) {
            throw new DomainRuleViolation('Images require alt.fr and alt.en.');
        }

        $media->writeCmsProperty('alt.fr', $altFr);
        $media->writeCmsProperty('rights', $rights);
        $media->save();

        return $media->refresh();
    }

    private function assertLength(string $field, string $value): void
    {
        if (strlen($value) > 255) {
            throw new DomainRuleViolation("Media {$field} must be 255 characters or fewer.");
        }
    }
}
