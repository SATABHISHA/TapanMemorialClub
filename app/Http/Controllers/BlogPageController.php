<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\View\View;

class BlogPageController extends Controller
{
    public function index(): View
    {
        $blogs = Blog::query()
            ->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            })
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('frontend.blogs.index', compact('blogs'));
    }

    public function show(string $slug): View
    {
        $blog = Blog::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $blog->increment('view_count');

        $related = Blog::query()
            ->where('status', 'published')
            ->where('id', '!=', $blog->id)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('frontend.blogs.show', compact('blog', 'related'));
    }
}
