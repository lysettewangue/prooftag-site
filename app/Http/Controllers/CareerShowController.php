<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Queries\CareerShowQuery;
use Illuminate\View\View;

final class CareerShowController extends Controller
{
    public function __invoke(string $slug, CareerShowQuery $query): View
    {
        return view('careers.show', $query->forSlug(app()->getLocale(), $slug));
    }
}
