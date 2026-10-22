<?php

declare(strict_types=1);

namespace App\Filament\Resources\Partners\Pages;

use App\Actions\Corporate\CreatePartner as CreatePartnerAction;
use App\Actions\Corporate\TranslatePartner;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Filament\Resources\Partners\PartnerResource;
use App\Models\Partner;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

final class CreatePartner extends CreateRecord
{
    protected static string $resource = PartnerResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            throw new Halt;
        }

        try {
            $partner = DB::transaction(function () use ($user, $data): Partner {
                $created = (new CreatePartnerAction)->handle($user, Arr::only($data, ['name', 'sort_order', 'url']));

                $french = $data['translations'][Locale::French->value] ?? [];

                if (! is_array($french)) {
                    $french = [];
                }

                (new TranslatePartner)->handle($created, $user, $this->translationPayload(Locale::French->value, $french));

                if ($user->can('translate', $created)) {
                    $english = $data['translations'][Locale::English->value] ?? null;

                    if (is_array($english) && (filled($english['slug'] ?? null) || filled($english['blurb'] ?? null))) {
                        (new TranslatePartner)->handle($created, $user, $this->translationPayload(Locale::English->value, $english));
                    }
                }

                return $created;
            });
        } catch (DomainRuleViolation $exception) {
            Notification::make()->danger()->title($exception->getMessage())->send();

            throw new Halt;
        } catch (AuthorizationException $exception) {
            Notification::make()->danger()->title('Action non autorisée.')->send();

            throw new Halt;
        }

        return $partner;
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    protected function translationPayload(string $locale, array $row): array
    {
        return array_merge(Arr::only($row, ['slug', 'blurb', 'meta_title', 'meta_description', 'canonical_path']), [
            'locale' => $locale,
        ]);
    }

    protected function getRedirectUrl(): string
    {
        $record = $this->getRecord();

        if (! $record instanceof Partner) {
            return PartnerResource::getUrl('index');
        }

        return PartnerResource::getUrl('edit', ['record' => $record]);
    }
}
