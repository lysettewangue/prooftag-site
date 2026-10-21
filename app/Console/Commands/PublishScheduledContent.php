<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\PublicationStatus;
use App\Support\ContentCache;
use App\Support\PublishableTypes;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

final class PublishScheduledContent extends Command
{
    protected $signature = 'content:publish-scheduled';

    protected $description = 'Move due scheduled content to published.';

    public function handle(): int
    {
        $now = now();
        $count = 0;

        foreach (PublishableTypes::classes() as $class) {
            $class::query()
                ->where('publication_status', PublicationStatus::Scheduled)
                ->whereNotNull('scheduled_for')
                ->where('scheduled_for', '<=', $now)
                ->each(function (Model $model) use ($now, &$count): void {
                    $model->forceFill([
                        'publication_status' => PublicationStatus::Published,
                        'published_at' => $now,
                        'scheduled_for' => null,
                    ])->save();

                    activity('staff')
                        ->performedOn($model)
                        ->event('published')
                        ->log('scheduled published');

                    $count++;
                });
        }

        if ($count > 0) {
            ContentCache::flush();
        }

        $this->info("Published {$count} scheduled record(s).");

        return self::SUCCESS;
    }
}
