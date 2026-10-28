<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PageCode;
use App\Queries\LegalPageQuery;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class LegalPageController extends Controller
{
    public function __invoke(Request $request, LegalPageQuery $query): View
    {
        $code = PageCode::from((string) $request->route('code', PageCode::LegalNotice->value));

        return view('legal.show', $query->forCode($code, app()->getLocale()));
    }
}
