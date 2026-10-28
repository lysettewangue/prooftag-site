<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Queries\PostShowQuery;
use Illuminate\View\View;

final class PostShowController extends Controller
{
    public function __invoke(string $slug, PostShowQuery $query): View
    {
        return view('news.show', $query->forSlug(app()->getLocale(), $slug));
    }
}
