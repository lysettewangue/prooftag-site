<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Queries\CentreShowQuery;
use Illuminate\View\View;

final class CentreShowController extends Controller
{
    public function __invoke(string $slug, CentreShowQuery $query): View
    {
        return view('centres.show', $query->forSlug(app()->getLocale(), $slug));
    }
}
