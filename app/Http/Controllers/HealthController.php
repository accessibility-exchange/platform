<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Events\DiagnosingHealth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class HealthController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $status = [];

        Event::dispatch(new DiagnosingHealth);

        if (defined('LARAVEL_START')) {
            $status['duration'] = round((microtime(true) - LARAVEL_START) * 1000).'ms';
        }

        return response()->json($status);
    }
}
