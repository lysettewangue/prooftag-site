<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Queries\ExpertiseShowQuery;
use Illuminate\View\View;

final class ExpertiseShowController extends Controller
{
    public function __invoke(string $slug, ExpertiseShowQuery $query): View
    {
        return view('expertises.show', $query->forSlug(app()->getLocale(), $slug));
    }
}
