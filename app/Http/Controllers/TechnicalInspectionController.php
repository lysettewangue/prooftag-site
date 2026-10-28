<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Queries\TechnicalInspectionHubQuery;
use Illuminate\View\View;

final class TechnicalInspectionController extends Controller
{
    public function __invoke(TechnicalInspectionHubQuery $query): View
    {
        return view('technical-inspection.hub', $query->forLocale(app()->getLocale()));
    }
}
