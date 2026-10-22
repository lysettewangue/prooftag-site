<?php

declare(strict_types=1);

namespace App\Actions\Media;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Domain\Media\MediaRules;
use App\Models\Media;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

final class TranslateMedia
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Media $media, User $actor, array $data): Media
    {
        Gate::forUser($actor)->authorize('translate', $media);

        $altEn = array_key_exists('alt.en', $data)
            ? trim((string) $data['alt.en'])
            : $media->altEn();

        if (strlen($altEn) > 255) {
            throw new DomainRuleViolation('Media alt.en must be 255 characters or fewer.');
        }

        $altFr = $media->altFr();

        if (MediaRules::isImage((string) $media->mime_type) && ($altFr === '' || $altEn === '')) {
            throw new DomainRuleViolation('Images require alt.fr and alt.en.');
        }

        $media->writeCmsProperty('alt.en', $altEn);
        $media->save();

        return $media->refresh();
    }
}
