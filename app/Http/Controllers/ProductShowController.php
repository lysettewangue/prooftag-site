<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\ProductCode;
use App\Queries\ProductShowQuery;
use Illuminate\View\View;

final class ProductShowController extends Controller
{
    public function __invoke(string $slug, ProductShowQuery $query): View
    {
        $data = $query->forSlug(app()->getLocale(), $slug);
        $view = match ($data['product']->code) {
            ProductCode::CertidocsCt => 'products.certidocs-ct',
            ProductCode::CertidocsCtReporting => 'products.certidocs-ct-reporting',
            ProductCode::CtVerif => 'products.ct-verif',
            ProductCode::Doser => 'products.doser',
            ProductCode::VideoEnforcement => 'products.video-enforcement',
            default => 'products.show',
        };

        return view($view, $data);
    }
}
