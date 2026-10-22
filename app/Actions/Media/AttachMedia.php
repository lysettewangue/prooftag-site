<?php

declare(strict_types=1);

namespace App\Actions\Media;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Domain\Media\MediaRules;
use App\Enums\Locale;
use App\Models\Media;
use App\Models\User;
use App\Support\StaffAccess;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\HasMedia;

final class AttachMedia
{
    /**
     * @param  array<string, mixed>  $properties
     */
    public function handle(HasMedia&Model $owner, User $actor, UploadedFile $file, string $collection, array $properties = []): Media
    {
        if (! StaffAccess::isStaff($actor)) {
            throw new DomainRuleViolation('Only staff may attach media.');
        }

        $mime = (string) $file->getMimeType();

        if (! MediaRules::isAllowed($mime)) {
            throw new DomainRuleViolation("MIME type {$mime} is not allowed.");
        }

        if ($file->getSize() > MediaRules::maxBytes()) {
            throw new DomainRuleViolation('File exceeds the 8 MB limit.');
        }

        if (MediaRules::isImage($mime)) {
            $altFr = (string) ($properties['alt'][Locale::French->value] ?? $properties['alt.fr'] ?? '');
            $altEn = (string) ($properties['alt'][Locale::English->value] ?? $properties['alt.en'] ?? '');

            if ($altFr === '' || $altEn === '') {
                throw new DomainRuleViolation('Images require alt.fr and alt.en.');
            }
        }

        $custom = [
            'alt.fr' => (string) ($properties['alt'][Locale::French->value] ?? $properties['alt.fr'] ?? ''),
            'alt.en' => (string) ($properties['alt'][Locale::English->value] ?? $properties['alt.en'] ?? ''),
            'rights' => (string) ($properties['rights'] ?? ''),
        ];

        $media = $owner
            ->addMedia($file)
            ->withCustomProperties($custom)
            ->toMediaCollection($collection);

        if (! $media instanceof Media) {
            throw new DomainRuleViolation('Attached media must use the CMS media model.');
        }

        return $media;
    }
}
