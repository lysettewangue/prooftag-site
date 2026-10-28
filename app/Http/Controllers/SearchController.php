<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Queries\SearchPageQuery;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class SearchController extends Controller
{
    public function __invoke(Request $request, SearchPageQuery $query): View
    {
        $q = $request->query('q');

        return view(
            'search.show',
            $query->forLocale(
                app()->getLocale(),
                is_string($q) ? $q : null,
            ),
        );
    }
}
