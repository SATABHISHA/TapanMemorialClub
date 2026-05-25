<?php

namespace App\Http\Controllers;

use App\Models\DynamicPage;
use Illuminate\View\View;

class DynamicPageController extends Controller
{
    public function show(string $slug): View
    {
        $page = DynamicPage::query()
            ->with(['media', 'menu'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $page->increment('view_count');

        $relatedPages = DynamicPage::query()
            ->where('is_published', true)
            ->whereKeyNot($page->id)
            ->with('media')
            ->orderBy('sort_order')
            ->latest('id')
            ->limit(4)
            ->get();

        return view('frontend.pages.show', compact('page', 'relatedPages'));
    }
}