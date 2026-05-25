<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PerformanceService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PerformanceApiController extends Controller
{
    public function __construct(private readonly PerformanceService $performanceService)
    {
    }

    public function chart(Request $request): JsonResponse
    {
        $year = $request->integer('year');

        $cacheKey = 'api.performance.chart.year.' . max(0, $year);
        $payload = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($year) {
            $performances = $this->performanceService->listForFrontend();
            if ($year > 0) {
                $performances = $performances->where('year', $year)->values();
            }

            return [
                // Chart.js renders array-per-label as multi-line text — year on top, tournament below.
                'labels' => $performances->map(fn ($p) => array_filter([
                    $p->year ? (string) $p->year : null,
                    (string) $p->tournament,
                ]))->values(),
                'wins' => $performances->pluck('wins'),
                'points' => $performances->pluck('points'),
            ];
        });

        return response()->json($payload)
            ->header('Cache-Control', 'public, max-age=60');
    }
}
