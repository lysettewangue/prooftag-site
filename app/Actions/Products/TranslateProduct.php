<?php

declare(strict_types=1);

namespace App\Actions\Products;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

final class TranslateProduct
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Product $product, User $actor, array $data): ProductTranslation
    {
        Gate::forUser($actor)->authorize('translate', $product);

        $locale = Locale::from((string) $data['locale']);
        $slug = $product->slug_fragment;

        if (isset($data['slug']) && $data['slug'] !== $slug) {
            throw new DomainRuleViolation('Product slugs must equal the frozen fragment.');
        }

        $translation = $product->translations()->updateOrCreate(
            ['locale' => $locale->value],
            array_merge(
                Arr::only($data, ['summary', 'body', 'external_label', 'meta_title', 'meta_description', 'canonical_path']),
                ['slug' => $slug],
            ),
        );

        if (! $translation instanceof ProductTranslation) {
            throw new DomainRuleViolation('Could not persist the product translation.');
        }

        ContentCache::flush();

        return $translation;
    }
}
