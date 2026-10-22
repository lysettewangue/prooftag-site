<?php

declare(strict_types=1);

namespace App\Filament\Resources\Posts\Pages;

use App\Actions\Editorial\CreatePost as CreatePostAction;
use App\Actions\Editorial\TranslatePost;
use App\Actions\Editorial\UpdatePost;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Filament\Resources\Posts\PostResource;
use App\Models\Post;
use App\Models\User;
use App\Support\BodyContent;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

final class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

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
            $post = DB::transaction(function () use ($user, $data): Post {
                $created = (new CreatePostAction)->handle($user, Arr::only($data, ['kind']));

                $french = $data['translations'][Locale::French->value] ?? null;

                if (! is_array($french)) {
                    throw new DomainRuleViolation('French title is required.');
                }

                (new TranslatePost)->handle($created, $user, $this->translationPayload(Locale::French->value, $french));

                if ($user->can('translate', $created)) {
                    $english = $data['translations'][Locale::English->value] ?? null;

                    if (is_array($english) && filled($english['title'] ?? null)) {
                        (new TranslatePost)->handle($created, $user, $this->translationPayload(Locale::English->value, $english));
                    }
                }

                (new UpdatePost)->handle($created, $user, Arr::only($data, ['kind', 'category_ids', 'product_ids', 'expertise_ids']));

                return $created;
            });
        } catch (DomainRuleViolation $exception) {
            Notification::make()->danger()->title($exception->getMessage())->send();

            throw new Halt;
        } catch (AuthorizationException $exception) {
            Notification::make()->danger()->title('Action non autorisée.')->send();

            throw new Halt;
        }

        return $post;
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    protected function translationPayload(string $locale, array $row): array
    {
        return array_merge(Arr::only($row, ['slug', 'title', 'summary', 'meta_title', 'meta_description', 'canonical_path']), [
            'locale' => $locale,
            'body' => BodyContent::fromForm($row['body'] ?? null),
        ]);
    }

    protected function getRedirectUrl(): string
    {
        $record = $this->getRecord();

        if (! $record instanceof Post) {
            return PostResource::getUrl('index');
        }

        return PostResource::getUrl('edit', ['record' => $record]);
    }
}
