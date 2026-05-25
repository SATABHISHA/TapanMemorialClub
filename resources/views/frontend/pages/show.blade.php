@extends('layouts.frontend')

@section('content')
@php
    $heroImage = $page->media_library_id ? route('media.show', $page->media_library_id) : null;
    $sidebarMenus = $globalMenus->take(5);
@endphp

<article class="club-page-shell">
    <header class="club-page-hero">
        <div class="club-page-hero__bg" aria-hidden="true">
            @if($heroImage)
                <img src="{{ $heroImage }}" alt="{{ $page->title }}">
            @endif
            <div class="club-page-hero__overlay"></div>
            <div class="club-page-hero__mesh"></div>
        </div>

        <div class="container club-page-hero__inner">
            <a href="{{ route('home') }}#club-pages" class="blog-back"><i class="bi bi-arrow-left"></i> Back To Club Pages</a>
            <span class="club-page-hero__eyebrow">{{ $page->menu?->title ?: 'Dynamic Club Page' }}</span>
            <h1 class="club-page-hero__title" data-aos="fade-up">{{ $page->title }}</h1>
            @if($page->description)
                <p class="club-page-hero__summary" data-aos="fade-up" data-aos-delay="80">{{ $page->description }}</p>
            @endif
            <div class="club-page-hero__meta" data-aos="fade-up" data-aos-delay="140">
                <span><i class="bi bi-eye"></i> {{ $page->view_count }} views</span>
                <span class="dot"></span>
                <span><i class="bi bi-calendar3"></i> {{ $page->updated_at->format('d M Y') }}</span>
            </div>
        </div>
    </header>

    <div class="container club-page-body">
        <div class="row g-5">
            <div class="col-lg-8">
                <div class="club-page-content" data-aos="fade-up">
                    {!! nl2br(e($page->content)) !!}
                </div>
            </div>
            <aside class="col-lg-4">
                <div class="blog-side-card mb-4" data-aos="fade-left">
                    <span class="side-eyebrow">Page Guide</span>
                    <h5 class="mt-2 mb-2">{{ $page->title }}</h5>
                    <p class="mb-3">{{ \Illuminate\Support\Str::limit($page->description ?: strip_tags($page->content), 140) }}</p>
                    <div class="page-guide-stats">
                        <div>
                            <strong>{{ $page->view_count }}</strong>
                            <small>Views</small>
                        </div>
                        <div>
                            <strong>{{ $page->updated_at->format('d M') }}</strong>
                            <small>Updated</small>
                        </div>
                    </div>
                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('home') }}#club-pages" class="btn btn-gold btn-sm"><i class="bi bi-grid-1x2"></i> Back To Club Pages</a>
                        <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm"><i class="bi bi-house-door"></i> Home</a>
                    </div>
                </div>

                <div class="blog-side-card mb-4" data-aos="fade-left" data-aos-delay="60">
                    <span class="side-eyebrow">Explore TMC</span>
                    <ul class="related-list mt-3">
                        @foreach($sidebarMenus as $menu)
                            @php
                                $quickUrl = $menu->url ?: '#';
                                $quickHref = \Illuminate\Support\Str::startsWith($quickUrl, '#') ? route('home') . $quickUrl : $quickUrl;
                            @endphp
                            <li>
                                <a href="{{ $quickHref }}">
                                    <span class="related-icon"><i class="bi {{ $menu->icon ?: 'bi-link-45deg' }}"></i></span>
                                    <div>
                                        <strong>{{ $menu->title }}</strong>
                                        <small>{{ $menu->type === 'external' ? 'External Link' : 'Navigate site' }}</small>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</article>
@endsection