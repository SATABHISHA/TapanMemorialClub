<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\GalleryImage;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\Sponsor;
use App\Models\Vlog;
use App\Services\BlogService;
use App\Services\PerformanceService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly PerformanceService $performanceService,
        private readonly BlogService $blogService,
    ) {
    }

    public function index(): View
    {
        $sliders = Slider::query()->where('is_active', true)->orderBy('sort_order')->get();
        $performances = $this->performanceService->listForFrontend();
        $achievements = Achievement::query()->orderByDesc('year')->limit(8)->get();
        $blogs = $this->blogService->latest(6);
        $gallery = GalleryImage::query()
            ->whereNotNull('media_library_id')
            ->orderBy('display_order')
            ->latest()
            ->limit(24)
            ->get();
        $sponsors = Sponsor::query()->where('is_active', true)->orderBy('sort_order')->get();
        $vlogs = Vlog::published()
            ->with('image', 'menu')
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->limit(6)
            ->get();
        $introText = (string) Setting::query()->where('key', 'club_intro_text')->value('value');
        $performanceText = (string) Setting::query()->where('key', 'club_performance_text')->value('value');

        return view('frontend.home', compact(
            'sliders',
            'performances',
            'achievements',
            'blogs',
            'vlogs',
            'gallery',
            'sponsors',
            'introText',
            'performanceText'
        ));
    }
}
