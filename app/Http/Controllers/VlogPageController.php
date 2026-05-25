<?php

namespace App\Http\Controllers;

use App\Models\Vlog;
use Illuminate\View\View;

class VlogPageController extends Controller
{
    public function index(): View
    {
        $vlogs = Vlog::published()
            ->with('image', 'menu')
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->paginate(12);

        return view('frontend.vlogs.index', compact('vlogs'));
    }

    public function show(string $slug): View
    {
        $vlog = Vlog::published()
            ->with('image', 'menu', 'submenu', 'author')
            ->where('slug', $slug)
            ->firstOrFail();

        $vlog->increment('view_count');

        $related = Vlog::published()
            ->with('image')
            ->where('id', '!=', $vlog->id)
            ->when($vlog->menu_id, fn ($q) => $q->where('menu_id', $vlog->menu_id))
            ->orderByDesc('published_at')
            ->limit(4)
            ->get();

        return view('frontend.vlogs.show', compact('vlog', 'related'));
    }
}
