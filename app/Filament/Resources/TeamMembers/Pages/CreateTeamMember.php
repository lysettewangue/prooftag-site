<?php

declare(strict_types=1);

namespace App\Filament\Resources\TeamMembers\Pages;

use App\Actions\Corporate\CreateTeamMember as CreateTeamMemberAction;
use App\Actions\Corporate\TranslateTeamMember;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Filament\Resources\TeamMembers\TeamMemberResource;
use App\Models\TeamMember;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

final class CreateTeamMember extends CreateRecord
{
    protected static string $resource = TeamMemberResource::class;

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
            $member = DB::transaction(function () use ($user, $data): TeamMember {
                $created = (new CreateTeamMemberAction)->handle($user, Arr::only($data, ['full_name', 'sort_order']));

                $french = $data['translations'][Locale::French->value] ?? null;

                if (! is_array($french)) {
                    throw new DomainRuleViolation('French title is required.');
                }

                (new TranslateTeamMember)->handle($created, $user, $this->translationPayload(Locale::French->value, $french));

                if ($user->can('translate', $created)) {
                    $english = $data['translations'][Locale::English->value] ?? null;

                    if (is_array($english) && filled($english['title'] ?? null)) {
                        (new TranslateTeamMember)->handle($created, $user, $this->translationPayload(Locale::English->value, $english));
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

        return $member;
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    protected function translationPayload(string $locale, array $row): array
    {
        return array_merge(Arr::only($row, ['slug', 'title', 'bio', 'meta_title', 'meta_description', 'canonical_path']), [
            'locale' => $locale,
        ]);
    }

    protected function getRedirectUrl(): string
    {
        $record = $this->getRecord();

        if (! $record instanceof TeamMember) {
            return TeamMemberResource::getUrl('index');
        }

        return TeamMemberResource::getUrl('edit', ['record' => $record]);
    }
}
