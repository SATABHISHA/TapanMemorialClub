@extends('layouts.frontend')

@section('content')
<section class="vlog-hero py-5 mt-5">
    <div class="container py-5">
        <span class="section-eyebrow"><i class="bi bi-newspaper"></i> Pavilion Buzz</span>
        <h1 class="display-4 fw-bold text-white mt-2">News &amp; <span class="gradient-text">Stories</span></h1>
        <p class="lead text-white-50 mb-0">Press releases, match reports and behind-the-scenes from Tapan Memorial Club.</p>
    </div>
</section>

<section class="container pb-5">
    @if($blogs->count() === 0)
        <div class="glass-card p-5 text-center text-white-50">No stories published yet.</div>
    @else
    <div class="row g-4">
        @foreach($blogs as $blog)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('blogs.show', $blog->slug) }}" class="vlog-card glass-card d-block h-100 text-decoration-none overflow-hidden">
                    <div class="vlog-media">
                        @if($blog->thumbnail_media_id)
                            <img src="{{ route('media.show', $blog->thumbnail_media_id) }}" alt="{{ $blog->title }}" loading="lazy">
                        @else
                            <div class="vlog-fallback"><i class="bi bi-megaphone-fill"></i></div>
                        @endif
                        @if($blog->youtube_url)<span class="vlog-play"><i class="bi bi-play-fill"></i></span>@endif
                    </div>
                    <div class="p-3">
                        <span class="vlog-tag">News</span>
                        <h5 class="text-white mt-2 mb-2">{{ $blog->title }}</h5>
                        <p class="text-white-50 small mb-2">{{ \Illuminate\Support\Str::limit($blog->excerpt, 110) }}</p>
                        <small class="text-warning">{{ optional($blog->published_at)->format('d M Y') }}</small>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $blogs->links() }}</div>
    @endif
</section>
@endsection
