<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Media\MediaRules;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    public function altFr(): string
    {
        return (string) $this->getCustomProperty('alt.fr', '');
    }

    public function altEn(): string
    {
        return (string) $this->getCustomProperty('alt.en', '');
    }

    public function rights(): string
    {
        return (string) $this->getCustomProperty('rights', '');
    }

    public function isImage(): bool
    {
        return MediaRules::isImage((string) $this->mime_type);
    }

    public function writeCmsProperty(string $key, string $value): void
    {
        $properties = $this->custom_properties;
        $properties[$key] = $value;
        $this->custom_properties = $properties;
    }
}
