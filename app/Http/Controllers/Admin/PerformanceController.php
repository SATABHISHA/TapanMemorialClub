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
        $isTournamentRecordsVisible = (string) Setting::query()
            ->where('key', 'club_tournament_records_visible')
            ->value('value') !== '0';
        $isPerformanceRecapVisible = (string) Setting::query()
            ->where('key', 'club_performance_recap_visible')
            ->value('value') !== '0';
        $isPerformanceSummaryVisible = (string) Setting::query()
            ->where('key', 'club_performance_summary_visible')
            ->value('value') !== '0';
        $isIntroductionFeatureCardsVisible = (string) Setting::query()
            ->where('key', 'club_introduction_feature_cards_visible')
            ->value('value') !== '0';

        return view('admin.performances.index', compact(
            'performances',
            'performanceText',
            'isTournamentRecordsVisible',
            'isPerformanceRecapVisible',
            'isPerformanceSummaryVisible',
            'isIntroductionFeatureCardsVisible'
        ));
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
     * Toggle homepage performance section visibility.
     */
    public function updateVisibility(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'club_tournament_records_visible' => ['nullable', 'boolean'],
            'club_performance_recap_visible' => ['nullable', 'boolean'],
            'club_performance_summary_visible' => ['nullable', 'boolean'],
            'club_introduction_feature_cards_visible' => ['nullable', 'boolean'],
        ]);

        if (array_key_exists('club_introduction_feature_cards_visible', $data)) {
            $isIntroductionFeatureCardsVisible = (bool) $data['club_introduction_feature_cards_visible'];

            Setting::query()->updateOrCreate(
                ['key' => 'club_introduction_feature_cards_visible'],
                [
                    'group' => 'club',
                    'type' => 'boolean',
                    'is_public' => true,
                    'value' => $isIntroductionFeatureCardsVisible ? '1' : '0',
                ]
            );

            return back()->with('status', $isIntroductionFeatureCardsVisible
                ? 'Introduction feature cards are now visible on the website.'
                : 'Introduction feature cards are now hidden from the website.');
        }

        if (array_key_exists('club_performance_summary_visible', $data)) {
            $isPerformanceSummaryVisible = (bool) $data['club_performance_summary_visible'];

            Setting::query()->updateOrCreate(
                ['key' => 'club_performance_summary_visible'],
                [
                    'group' => 'club',
                    'type' => 'boolean',
                    'is_public' => true,
                    'value' => $isPerformanceSummaryVisible ? '1' : '0',
                ]
            );

            return back()->with('status', $isPerformanceSummaryVisible
                ? 'Performance summary section is now visible on the website.'
                : 'Performance summary section is now hidden from the website.');
        }

        if (array_key_exists('club_tournament_records_visible', $data)) {
            $isTournamentRecordsVisible = (bool) $data['club_tournament_records_visible'];

            Setting::query()->updateOrCreate(
                ['key' => 'club_tournament_records_visible'],
                [
                    'group' => 'club',
                    'type' => 'boolean',
                    'is_public' => true,
                    'value' => $isTournamentRecordsVisible ? '1' : '0',
                ]
            );

            return back()->with('status', $isTournamentRecordsVisible
                ? 'Tournament records section is now visible on the website.'
                : 'Tournament records section is now hidden from the website.');
        }

        if (array_key_exists('club_performance_recap_visible', $data)) {
            $isPerformanceRecapVisible = (bool) $data['club_performance_recap_visible'];

            Setting::query()->updateOrCreate(
                ['key' => 'club_performance_recap_visible'],
                [
                    'group' => 'club',
                    'type' => 'boolean',
                    'is_public' => true,
                    'value' => $isPerformanceRecapVisible ? '1' : '0',
                ]
            );

            return back()->with('status', $isPerformanceRecapVisible
                ? 'Performance recap section is now visible on the website.'
                : 'Performance recap section is now hidden from the website.');
        }

        return back()->with('status', 'No visibility changes were submitted.');
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
