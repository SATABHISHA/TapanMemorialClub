<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\DynamicPage;
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
        $sliders = Slider::query()
            ->with('media:id,width,height')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Show all active slider images and keep it fully dynamic for future additions.
        $heroSliders = $sliders->filter(fn (Slider $slider): bool => (bool) $slider->media_library_id)->values();
        $performances = $this->performanceService->listForFrontend();
        $achievements = Achievement::query()->orderByDesc('year')->limit(8)->get();
        $blogs = $this->blogService->latest(6);
        $gallery = GalleryImage::query()
            ->whereNotNull('media_library_id')
            ->orderBy('display_order')
            ->latest()
            ->limit(24)
            ->get();
        $founders = GalleryImage::query()
            ->whereNotNull('media_library_id')
            ->whereRaw('LOWER(COALESCE(category, "")) LIKE ?', ['founder%'])
            ->orderBy('display_order')
            ->latest()
            ->limit(8)
            ->get();
        $sponsors = Sponsor::query()->where('is_active', true)->orderBy('sort_order')->get();
        $vlogs = Vlog::published()
            ->with('image', 'menu')
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->limit(6)
            ->get();
        $historyPage = DynamicPage::query()
            ->where('slug', 'history-of-the-club')
            ->where('is_published', true)
            ->first();

        $introText = $historyPage
            ? (string) ($historyPage->content ?? '')
            : (string) Setting::query()->where('key', 'club_intro_text')->value('value');
        $performanceText = (string) Setting::query()->where('key', 'club_performance_text')->value('value');
        $historyTitle = (string) ($historyPage?->title ?? 'The Club Story');
        $historySummary = (string) ($historyPage?->description ?? 'Eighty-plus years of bat, ball, and brotherhood — woven into the maroon-and-blue fabric of Kolkata cricket.');

        return view('frontend.home', compact(
            'sliders',
            'heroSliders',
            'performances',
            'achievements',
            'blogs',
            'vlogs',
            'gallery',
            'founders',
            'sponsors',
            'introText',
            'performanceText',
            'historyPage',
            'historyTitle',
            'historySummary'
        ));
    }
}
