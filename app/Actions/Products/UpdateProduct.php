<?php

declare(strict_types=1);

namespace App\Actions\Products;

use App\Models\Product;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

final class UpdateProduct
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Product $product, User $actor, array $data): Product
    {
        Gate::forUser($actor)->authorize('update', $product);

        $product->fill(Arr::only($data, ['external_url']));
        $product->save();

        if (array_key_exists('expertise_ids', $data) && is_array($data['expertise_ids'])) {
            $product->expertises()->sync($data['expertise_ids']);
        }

        ContentCache::flush();

        return $product->refresh();
    }
}
