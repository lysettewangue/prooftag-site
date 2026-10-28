<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Queries\ProductIndexQuery;
use Illuminate\View\View;

final class ProductIndexController extends Controller
{
    public function __invoke(ProductIndexQuery $query): View
    {
        return view('products.index', $query->forLocale(app()->getLocale()));
    }
}
