@extends('layouts.frontend')

@section('content')
<section class="vlog-hero py-5 mt-5">
    <div class="container py-5">
        <span class="section-eyebrow"><i class="bi bi-camera-reels"></i> Vlogs &amp; Stories</span>
        <h1 class="display-4 fw-bold text-white mt-2">Inside <span class="gradient-text">The Club</span></h1>
        <p class="lead text-white-50 mb-0">Match reports, player diaries, monsoon memories and every voice from the dressing room.</p>
    </div>
</section>

<section class="container pb-5">
    @if($vlogs->count() === 0)
        <div class="glass-card p-5 text-center text-white-50">No vlogs published yet. Check back soon.</div>
    @else
    <div class="row g-4">
        @foreach($vlogs as $vlog)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('vlogs.show', $vlog->slug) }}" class="vlog-card glass-card d-block h-100 text-decoration-none overflow-hidden">
                    <div class="vlog-media">
                        @if($vlog->image_media_id)
                            <img src="{{ route('media.show', $vlog->image_media_id) }}" alt="{{ $vlog->title }}" loading="lazy">
                        @else
                            <div class="vlog-fallback"><i class="bi bi-play-btn"></i></div>
                        @endif
                        @if($vlog->video_url)<span class="vlog-play"><i class="bi bi-play-fill"></i></span>@endif
                        @if($vlog->is_featured)<span class="vlog-badge">Featured</span>@endif
                    </div>
                    <div class="p-3">
                        @if($vlog->menu)<span class="vlog-tag">{{ $vlog->menu->title }}</span>@endif
                        <h5 class="text-white mt-2 mb-2">{{ $vlog->title }}</h5>
                        <p class="text-white-50 small mb-2">{{ \Illuminate\Support\Str::limit($vlog->description, 110) }}</p>
                        <small class="text-warning">{{ optional($vlog->published_at)->format('d M Y') }}</small>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $vlogs->links() }}</div>
    @endif
</section>
@endsection
