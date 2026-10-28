<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Queries\NewsIndexQuery;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class NewsIndexController extends Controller
{
    public function __invoke(Request $request, NewsIndexQuery $query): View
    {
        $kind = $request->query('kind');
        $category = $request->query('category');

        return view(
            'news.index',
            $query->forLocale(
                app()->getLocale(),
                is_string($kind) ? $kind : null,
                is_string($category) ? $category : null,
            ),
        );
    }
}
