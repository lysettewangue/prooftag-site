<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

final class CmsSchema
{
    /**
     * @var list<string>
     */
    public const PUBLICATION_STATUSES = [
        'draft',
        'under_review',
        'approved',
        'scheduled',
        'published',
        'archived',
    ];

    public static function publishableColumns(Blueprint $table): void
    {
        $table->string('publication_status', 32);
        $table->timestamp('published_at')->nullable();
        $table->timestamp('scheduled_for')->nullable();
        $table->timestamps();
        $table->softDeletes();
    }

    public static function publicationIndexes(Blueprint $table, bool $withSortOrder = false): void
    {
        if ($withSortOrder) {
            $table->index(['publication_status', 'published_at', 'sort_order']);
        } else {
            $table->index(['publication_status', 'published_at']);
        }

        $table->index(['publication_status', 'scheduled_for']);
    }

    public static function translationKeys(
        Blueprint $table,
        string $parentTable,
        string $foreignKey,
        bool $withSlug = true,
    ): void {
        $table->id();
        $table->foreignId($foreignKey)->constrained($parentTable)->cascadeOnDelete();
        $table->char('locale', 2);

        if ($withSlug) {
            $table->string('slug', 191);
        }
    }

    public static function translationSeo(Blueprint $table, bool $withTimestamps = true): void
    {
        $table->string('meta_title')->nullable();
        $table->string('meta_description')->nullable();
        $table->string('canonical_path')->nullable();

        if ($withTimestamps) {
            $table->timestamps();
        }
    }

    public static function translationUniques(Blueprint $table, string $foreignKey, bool $withSlug = true): void
    {
        $table->unique([$foreignKey, 'locale'], $table->getTable().'_parent_locale_uq');

        if ($withSlug) {
            $table->unique(['locale', 'slug'], $table->getTable().'_locale_slug_uq');
        }
    }

    /**
     * @param  list<string>  $values
     */
    public static function checkIn(string $table, string $column, array $values): void
    {
        $quoted = implode(', ', array_map(
            static fn (string $value): string => "'".str_replace("'", "''", $value)."'",
            $values,
        ));

        $identifier = in_array($column, ['set'], true) ? "`{$column}`" : $column;

        DB::statement("ALTER TABLE {$table} ADD CONSTRAINT {$table}_{$column}_check CHECK ({$identifier} IN ({$quoted}))");
    }

    public static function checkLocale(string $table): void
    {
        self::checkIn($table, 'locale', ['fr', 'en']);
    }

    public static function checkPublication(string $table): void
    {
        self::checkIn($table, 'publication_status', self::PUBLICATION_STATUSES);
    }
}
