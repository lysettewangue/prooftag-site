<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PageCode;
use App\Models\Page;
use App\Queries\CorporatePageQuery;
use App\Support\PublicPath;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class CorporatePageController extends Controller
{
    public function __invoke(Request $request, CorporatePageQuery $query): View|RedirectResponse
    {
        $code = PageCode::from((string) $request->route('code', PageCode::About->value));
        $locale = app()->getLocale();

        if ($code !== PageCode::About) {
            $aboutVisible = Page::query()
                ->where('code', PageCode::About)
                ->publiclyVisible(now())
                ->forLocale($locale)
                ->exists();

            if (! $aboutVisible) {
                abort(404);
            }

            $aboutUrl = PublicPath::localized(
                $locale,
                PageCode::About->slugs()[$locale],
            );

            return redirect()->to($aboutUrl.'#'.$code->value, 302);
        }

        return view('pages.corporate', $query->forCode($code, $locale));
    }
}
