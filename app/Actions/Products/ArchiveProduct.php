<?php

declare(strict_types=1);

namespace App\Actions\Products;

use App\Actions\Publication\TransitionPublication;
use App\Enums\PublicationStatus;
use App\Models\Product;
use App\Models\User;

final class ArchiveProduct
{
    public function handle(Product $product, User $actor): Product
    {
        /** @var Product $product */
        $product = (new TransitionPublication)->handle($product, PublicationStatus::Archived, $actor);

        return $product;
    }
}
