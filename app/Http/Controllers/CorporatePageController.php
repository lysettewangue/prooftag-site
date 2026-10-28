<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PageCode;
use App\Queries\CorporatePageQuery;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class CorporatePageController extends Controller
{
    public function __invoke(Request $request, CorporatePageQuery $query): View
    {
        $code = PageCode::from((string) $request->route('code', PageCode::About->value));

        return view('pages.corporate', $query->forCode($code, app()->getLocale()));
    }
}
