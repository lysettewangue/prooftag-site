<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Queries\ResourceIndexQuery;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ResourceIndexController extends Controller
{
    public function __invoke(Request $request, ResourceIndexQuery $query): View
    {
        $status = $request->query('status');

        return view(
            'resources.index',
            $query->forLocale(
                app()->getLocale(),
                is_string($status) ? $status : null,
            ),
        );
    }
}
