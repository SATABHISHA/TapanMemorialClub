<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePerformanceRequest;
use App\Http\Requests\UpdatePerformanceRequest;
use App\Models\Performance;
use App\Models\Setting;
use App\Services\PerformanceService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PerformanceController extends Controller
{
    public function __construct(private readonly PerformanceService $performanceService)
    {
    }

    public function index(): View
    {
        $performances = $this->performanceService->paginateAdmin(20);
        $performanceText = (string) Setting::query()->where('key', 'club_performance_text')->value('value');

        return view('admin.performances.index', compact('performances', 'performanceText'));
    }

    /**
     * Update the public-facing Performance Recap paragraph (stored in settings).
     */
    public function updateRecapText(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'value' => ['nullable', 'string', 'max:10000'],
        ]);

        Setting::query()->updateOrCreate(
            ['key' => 'club_performance_text'],
            [
                'group' => 'club',
                'type' => 'textarea',
                'is_public' => true,
                'value' => $data['value'] ?? '',
            ]
        );

        return back()->with('status', 'Performance recap updated.');
    }

    /**
     * Forget cached payload(s) used by /api/performance-chart.
     */
    private function flushPerformanceChartCache(): void
    {
        Cache::forget('api.performance.chart.year.0');

        Performance::query()
            ->select('year')
            ->distinct()
            ->pluck('year')
            ->filter(fn ($y) => (int) $y > 0)
            ->each(fn ($year) => Cache::forget('api.performance.chart.year.' . (int) $year));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.performances.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePerformanceRequest $request): RedirectResponse
    {
        $this->performanceService->create($request->validated());
        $this->flushPerformanceChartCache();

        return back()->with('status', 'Performance added.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.performances.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('admin.performances.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePerformanceRequest $request, Performance $performance): RedirectResponse
    {
        $this->performanceService->update($performance, $request->validated());
        $this->flushPerformanceChartCache();

        return back()->with('status', 'Performance updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Performance $performance): RedirectResponse
    {
        $this->performanceService->delete($performance);
        $this->flushPerformanceChartCache();

        return back()->with('status', 'Performance deleted.');
    }
}
