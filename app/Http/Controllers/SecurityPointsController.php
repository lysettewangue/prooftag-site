<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Queries\SecurityPointsIndexQuery;
use Illuminate\View\View;

final class SecurityPointsController extends Controller
{
    public function __invoke(SecurityPointsIndexQuery $query): View
    {
        return view('technical-inspection.security-points', $query->forLocale(app()->getLocale()));
    }
}
