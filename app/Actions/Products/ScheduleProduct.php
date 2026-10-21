<?php

declare(strict_types=1);

namespace App\Actions\Products;

use App\Actions\Publication\TransitionPublication;
use App\Enums\PublicationStatus;
use App\Models\Product;
use App\Models\User;
use DateTimeInterface;

final class ScheduleProduct
{
    public function handle(Product $product, User $actor, DateTimeInterface $when): Product
    {
        /** @var Product $product */
        $product = (new TransitionPublication)->handle($product, PublicationStatus::Scheduled, $actor, $when);

        return $product;
    }
}
