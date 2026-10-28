<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Queries\EquipmentShowQuery;
use Illuminate\View\View;

final class EquipmentShowController extends Controller
{
    public function __invoke(string $slug, EquipmentShowQuery $query): View
    {
        return view('technical-inspection.equipment-show', $query->forSlug(app()->getLocale(), $slug));
    }
}
