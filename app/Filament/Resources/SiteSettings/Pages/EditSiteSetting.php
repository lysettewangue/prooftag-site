<?php

declare(strict_types=1);

namespace App\Filament\Resources\SiteSettings\Pages;

use App\Actions\Corporate\TranslateSiteSettings;
use App\Actions\Corporate\UpdateSiteSettings;
use App\Actions\Media\AttachMedia;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Filament\Resources\SiteSettings\SiteSettingResource;
use App\Filament\Support\SavesViaDomainActions;
use App\Models\SiteSetting;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

final class EditSiteSetting extends EditRecord
{
    use SavesViaDomainActions;

    protected static string $resource = SiteSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function parentPayload(array $data): array
    {
        return Arr::only($data, [
            'social_linkedin',
            'social_facebook',
            'social_x',
            'social_youtube',
            'social_instagram',
            'geo_latitude',
            'geo_longitude',
        ]);
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    protected function translationPayload(string $locale, array $row): array
    {
        return array_merge(Arr::only($row, ['tagline', 'meta_title', 'meta_description', 'canonical_path']), [
            'locale' => $locale,
        ]);
    }

    protected function performUpdate(Model $record, User $actor, array $payload): void
    {
        if (! $record instanceof SiteSetting) {
            throw new Halt;
        }

        (new UpdateSiteSettings)->handle($record, $actor, $payload);
    }

    protected function performTranslate(Model $record, User $actor, array $payload): void
    {
        if (! $record instanceof SiteSetting) {
            throw new Halt;
        }

        (new TranslateSiteSettings)->handle($record, $actor, $payload);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $logo = $data['logo'] ?? null;
        $altFr = is_string($data['logo_alt_fr'] ?? null) ? $data['logo_alt_fr'] : '';
        $altEn = is_string($data['logo_alt_en'] ?? null) ? $data['logo_alt_en'] : '';
        unset($data['logo'], $data['logo_alt_fr'], $data['logo_alt_en']);

        $record = parent::handleRecordUpdate($record, $data);

        $file = is_array($logo) ? (array_values($logo)[0] ?? null) : $logo;

        if ($file instanceof UploadedFile && $record instanceof SiteSetting) {
            try {
                (new AttachMedia)->handle($record, $this->actor(), $file, 'images', [
                    'alt.fr' => $altFr,
                    'alt.en' => $altEn,
                    'rights' => 'PROOFTAG-CATIS',
                ]);
            } catch (DomainRuleViolation $exception) {
                Notification::make()->danger()->title($exception->getMessage())->send();

                throw new Halt;
            }
        }

        return $record->refresh();
    }
}
