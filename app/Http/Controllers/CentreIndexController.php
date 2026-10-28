<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Queries\CentreIndexQuery;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class CentreIndexController extends Controller
{
    public function __invoke(Request $request, CentreIndexQuery $query): View
    {
        $region = $request->query('region');
        $regionId = is_numeric($region) ? (int) $region : null;
        $search = $request->query('q');

        return view(
            'centres.index',
            $query->forLocale(
                app()->getLocale(),
                is_string($search) ? $search : null,
                $regionId,
            ),
        );
    }
}
