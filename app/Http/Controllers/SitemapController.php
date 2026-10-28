<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Queries\SitemapQuery;
use Illuminate\Http\Response;

final class SitemapController extends Controller
{
    public function __invoke(SitemapQuery $sitemap): Response
    {
        return response()
            ->view('seo.sitemap', ['urls' => $sitemap->urls()], 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
