<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\ExpertiseCode;
use App\Queries\ExpertiseShowQuery;
use Illuminate\View\View;

final class ExpertiseShowController extends Controller
{
    public function __invoke(string $slug, ExpertiseShowQuery $query): View
    {
        $data = $query->forSlug(app()->getLocale(), $slug);
        $view = match ($data['expertise']->code) {
            ExpertiseCode::ResearchDevelopment => 'expertises.research-development',
            ExpertiseCode::TechnicalSupport => 'expertises.technical-support',
            ExpertiseCode::DataAnalysisReporting => 'expertises.data-analysis-reporting',
            ExpertiseCode::DocumentSecurity => 'expertises.document-security',
            ExpertiseCode::RoadSafetyPrevention => 'expertises.road-safety-prevention',
            default => 'expertises.show',
        };

        return view($view, $data);
    }
}
