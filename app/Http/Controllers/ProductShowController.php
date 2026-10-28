<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Queries\ProductShowQuery;
use Illuminate\View\View;

final class ProductShowController extends Controller
{
    public function __invoke(string $slug, ProductShowQuery $query): View
    {
        return view('products.show', $query->forSlug(app()->getLocale(), $slug));
    }
}
