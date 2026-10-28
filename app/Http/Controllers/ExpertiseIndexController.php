<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Queries\ExpertiseIndexQuery;
use Illuminate\View\View;

final class ExpertiseIndexController extends Controller
{
    public function __invoke(ExpertiseIndexQuery $query): View
    {
        return view('expertises.index', $query->forLocale(app()->getLocale()));
    }
}
