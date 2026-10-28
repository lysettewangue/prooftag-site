<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Queries\ResourceShowQuery;
use Illuminate\View\View;

final class ResourceShowController extends Controller
{
    public function __invoke(string $slug, ResourceShowQuery $query): View
    {
        return view('resources.show', $query->forSlug(app()->getLocale(), $slug));
    }
}
