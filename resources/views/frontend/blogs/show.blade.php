@extends('layouts.frontend')

@section('content')
<article class="blog-detail">
    {{-- HERO --}}
    <header class="blog-hero">
        <div class="blog-hero-bg" aria-hidden="true">
            @if($blog->thumbnail_media_id)
                <img src="{{ route('media.show', $blog->thumbnail_media_id) }}" alt="">
            @endif
            <div class="blog-hero-overlay"></div>
            <div class="blog-hero-stripes"></div>
        </div>

        <div class="container blog-hero-inner">
            <a href="{{ route('blogs.index') }}" class="blog-back"><i class="bi bi-arrow-left"></i> All News</a>
            <span class="blog-eyebrow"><i class="bi bi-newspaper"></i> Tapan Memorial Club · Pavilion Buzz</span>
            <h1 class="blog-title" data-aos="fade-up">{{ $blog->title }}</h1>
            <div class="blog-meta">
                <span><i class="bi bi-calendar3"></i> {{ optional($blog->published_at)->format('d M Y') ?: $blog->created_at->format('d M Y') }}</span>
                <span class="dot"></span>
                <span><i class="bi bi-eye"></i> {{ $blog->view_count }} views</span>
                @if($blog->youtube_url)
                    <span class="dot"></span>
                    <span><i class="bi bi-play-btn-fill text-warning"></i> Video Story</span>
                @endif
            </div>
        </div>

        <div class="blog-hero-scroll"><i class="bi bi-chevron-double-down"></i></div>
    </header>

    {{-- BODY --}}
    <div class="container blog-body-wrap">
        <div class="row g-5">
            <div class="col-lg-8">
                @if($blog->youtube_url)
                    @php
                        $ytId = null;
                        if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|shorts/))([\w-]{11})~', $blog->youtube_url, $m)) { $ytId = $m[1]; }
                    @endphp
                    @if($ytId)
                        <div class="ratio ratio-16x9 blog-video mb-4" data-aos="zoom-in">
                            <iframe src="https://www.youtube.com/embed/{{ $ytId }}" title="{{ $blog->title }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    @endif
                @endif

                @if($blog->excerpt)
                    <p class="blog-excerpt" data-aos="fade-up">{{ $blog->excerpt }}</p>
                @endif

                <div class="blog-content" data-aos="fade-up" data-aos-delay="120">
                    {!! nl2br(e($blog->content)) !!}
                </div>

                <div class="blog-share mt-5" data-aos="fade-up">
                    <span class="share-label">Share</span>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($blog->title) }}&url={{ urlencode(request()->fullUrl()) }}" target="_blank"><i class="bi bi-twitter-x"></i></a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank"><i class="bi bi-facebook"></i></a>
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($blog->title.' '.request()->fullUrl()) }}" target="_blank"><i class="bi bi-whatsapp"></i></a>
                    <a href="mailto:?subject={{ urlencode($blog->title) }}&body={{ urlencode(request()->fullUrl()) }}"><i class="bi bi-envelope-fill"></i></a>
                </div>
            </div>

            <aside class="col-lg-4">
                <div class="blog-side-card" data-aos="fade-left">
                    <span class="side-eyebrow">About The Club</span>
                    <h5>Legacy. League. Champions.</h5>
                    <p>From the monsoon of 1942 to the floodlights of today — every story you read here is part of an eight-decade journey through Calcutta cricket.</p>
                    <a href="{{ route('home') }}#story" class="btn btn-gold btn-sm w-100"><i class="bi bi-book"></i> Read Our Heritage</a>
                </div>

                @if($related->count())
                    <div class="blog-side-card mt-4" data-aos="fade-left" data-aos-delay="100">
                        <span class="side-eyebrow">More Stories</span>
                        <ul class="related-list">
                            @foreach($related as $r)
                                <li>
                                    <a href="{{ route('blogs.show', $r->slug) }}">
                                        @if($r->thumbnail_media_id)
                                            <img src="{{ route('media.thumb', $r->thumbnail_media_id) }}" alt="">
                                        @else
                                            <span class="related-icon"><i class="bi bi-megaphone-fill"></i></span>
                                        @endif
                                        <div>
                                            <strong>{{ \Illuminate\Support\Str::limit($r->title, 60) }}</strong>
                                            <small>{{ optional($r->published_at)->format('d M Y') }}</small>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</article>
@endsection
