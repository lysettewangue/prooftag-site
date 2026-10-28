<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Resources\DownloadResource;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Queries\ResourceLibrary;
use Symfony\Component\HttpFoundation\Response;

final class ResourceDownloadController extends Controller
{
    public function __invoke(string $slug, ResourceLibrary $library, DownloadResource $download): Response
    {
        $locale = app()->getLocale();
        $resource = $library->findBySlug($locale, $slug);

        try {
            return $download->handle($resource, Locale::from($locale));
        } catch (DomainRuleViolation) {
            abort(404);
        }
    }
}
