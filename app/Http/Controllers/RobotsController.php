<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\RobotsTxt;
use Illuminate\Http\Response;

final class RobotsController extends Controller
{
    public function __invoke(): Response
    {
        return response(RobotsTxt::body(), 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}
