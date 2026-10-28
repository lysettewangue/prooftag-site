<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Queries\EquipmentIndexQuery;
use Illuminate\View\View;

final class EquipmentIndexController extends Controller
{
    public function __invoke(EquipmentIndexQuery $query): View
    {
        return view('technical-inspection.equipment-index', $query->forLocale(app()->getLocale()));
    }
}
