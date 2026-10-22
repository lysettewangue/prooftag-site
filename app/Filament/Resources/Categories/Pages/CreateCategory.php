<?php

declare(strict_types=1);

namespace App\Filament\Resources\Categories\Pages;

use App\Actions\Editorial\CreateCategory as CreateCategoryAction;
use App\Actions\Editorial\TranslateCategory;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Filament\Resources\Categories\CategoryResource;
use App\Models\Category;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

final class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

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
            $category = DB::transaction(function () use ($user, $data): Category {
                $created = (new CreateCategoryAction)->handle($user, Arr::only($data, ['sort_order']));

                $french = $data['translations'][Locale::French->value] ?? null;

                if (! is_array($french)) {
                    throw new DomainRuleViolation('French name is required.');
                }

                (new TranslateCategory)->handle($created, $user, $this->translationPayload(Locale::French->value, $french));

                if ($user->can('translate', $created)) {
                    $english = $data['translations'][Locale::English->value] ?? null;

                    if (is_array($english) && filled($english['name'] ?? null)) {
                        (new TranslateCategory)->handle($created, $user, $this->translationPayload(Locale::English->value, $english));
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

        return $category;
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    protected function translationPayload(string $locale, array $row): array
    {
        return array_merge(Arr::only($row, ['slug', 'name', 'meta_title', 'meta_description', 'canonical_path']), [
            'locale' => $locale,
        ]);
    }

    protected function getRedirectUrl(): string
    {
        $record = $this->getRecord();

        if (! $record instanceof Category) {
            return CategoryResource::getUrl('index');
        }

        return CategoryResource::getUrl('edit', ['record' => $record]);
    }
}
