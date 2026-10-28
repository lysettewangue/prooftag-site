<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Queries\HomePageQuery;
use Illuminate\View\View;

final class HomeController extends Controller
{
    public function __invoke(HomePageQuery $home): View
    {
        $payload = $home->forLocale(app()->getLocale());

        return view('home', $payload);
    }
}
