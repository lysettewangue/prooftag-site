<?php

declare(strict_types=1);

namespace App\Filament\Resources\LibraryDocuments\Pages;

use App\Actions\Media\AttachMedia;
use App\Actions\Resources\CreateResource as CreateResourceAction;
use App\Actions\Resources\TranslateResource;
use App\Actions\Resources\UpdateResource;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Filament\Resources\LibraryDocuments\LibraryDocumentResource;
use App\Models\Resource as CmsResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

final class CreateLibraryDocument extends CreateRecord
{
    protected static string $resource = LibraryDocumentResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            throw new Halt;
        }

        $document = $data['document'] ?? null;
        unset($data['document']);

        try {
            $resource = DB::transaction(function () use ($user, $data, $document): CmsResource {
                $created = (new CreateResourceAction)->handle($user, Arr::only($data, [
                    'regulatory_status',
                    'authority',
                    'issued_on',
                    'relates_to_ct',
                ]));

                $french = $data['translations'][Locale::French->value] ?? null;

                if (! is_array($french)) {
                    throw new DomainRuleViolation('French title is required.');
                }

                (new TranslateResource)->handle($created, $user, $this->translationPayload(Locale::French->value, $french));

                if ($user->can('translate', $created)) {
                    $english = $data['translations'][Locale::English->value] ?? null;

                    if (is_array($english) && filled($english['title'] ?? null)) {
                        (new TranslateResource)->handle($created, $user, $this->translationPayload(Locale::English->value, $english));
                    }
                }

                (new UpdateResource)->handle($created, $user, Arr::only($data, ['product_ids']));

                $file = is_array($document) ? (array_values($document)[0] ?? null) : $document;

                if ($file instanceof UploadedFile) {
                    (new AttachMedia)->handle($created, $user, $file, 'documents', [
                        'rights' => 'PROOFTAG-CATIS',
                    ]);
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

        return $resource;
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    protected function translationPayload(string $locale, array $row): array
    {
        return array_merge(Arr::only($row, ['slug', 'title', 'summary', 'meta_title', 'meta_description', 'canonical_path']), [
            'locale' => $locale,
        ]);
    }

    protected function getRedirectUrl(): string
    {
        $record = $this->getRecord();

        if (! $record instanceof CmsResource) {
            return LibraryDocumentResource::getUrl('index');
        }

        return LibraryDocumentResource::getUrl('edit', ['record' => $record]);
    }
}
