<?php

declare(strict_types=1);

namespace App\Filament\Resources\InspectionCentres\Pages;

use App\Actions\Centres\ImportCentre;
use App\Actions\Centres\TranslateCentre;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Filament\Resources\InspectionCentres\InspectionCentreResource;
use App\Models\InspectionCentre;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

final class CreateInspectionCentre extends CreateRecord
{
    protected static string $resource = InspectionCentreResource::class;

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
            $centre = DB::transaction(function () use ($user, $data): InspectionCentre {
                $french = is_array($data['translations'][Locale::French->value] ?? null)
                    ? $data['translations'][Locale::French->value]
                    : [];

                $created = (new ImportCentre)->handle($user, array_merge(
                    Arr::only($data, [
                        'region_id',
                        'operator_name',
                        'city',
                        'phone',
                        'email',
                        'latitude',
                        'longitude',
                        'sort_order',
                    ]),
                    $this->translationPayload(Locale::French->value, $french),
                ));

                if ($user->can('translate', $created)) {
                    $english = $data['translations'][Locale::English->value] ?? null;

                    if (is_array($english) && filled($english['name'] ?? null)) {
                        (new TranslateCentre)->handle($created, $user, $this->translationPayload(Locale::English->value, $english));
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

        return $centre;
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    protected function translationPayload(string $locale, array $row): array
    {
        return array_merge(Arr::only($row, [
            'slug',
            'name',
            'address_line',
            'body',
            'meta_title',
            'meta_description',
            'canonical_path',
        ]), [
            'locale' => $locale,
        ]);
    }

    protected function getRedirectUrl(): string
    {
        $record = $this->getRecord();

        if (! $record instanceof InspectionCentre) {
            return InspectionCentreResource::getUrl('index');
        }

        return InspectionCentreResource::getUrl('edit', ['record' => $record]);
    }
}
