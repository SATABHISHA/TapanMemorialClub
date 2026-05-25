@extends('layouts.frontend')

@section('content')
<article class="vlog-detail py-5 mt-5">
    <div class="container py-5">
        <a href="{{ route('vlogs.index') }}" class="text-warning small mb-3 d-inline-block"><i class="bi bi-arrow-left"></i> All Vlogs</a>
        @if($vlog->menu)<div class="mb-2"><span class="vlog-tag">{{ $vlog->menu->title }}</span></div>@endif
        <h1 class="display-5 fw-bold text-white">{{ $vlog->title }}</h1>
        <p class="text-white-50">
            {{ optional($vlog->published_at)->format('d M Y') }}
            @if($vlog->author) · by {{ $vlog->author->name }} @endif
            · <i class="bi bi-eye"></i> {{ $vlog->view_count }} views
        </p>

        @if($vlog->video_url)
            <div class="ratio ratio-16x9 my-4 rounded-4 overflow-hidden shadow-lg">
                @php
                    $url = $vlog->video_url;
                    $ytId = null;
                    if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|shorts/))([\w-]{11})~', $url, $m)) { $ytId = $m[1]; }
                @endphp
                @if($ytId)
                    <iframe src="https://www.youtube.com/embed/{{ $ytId }}" title="{{ $vlog->title }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                @else
                    <video controls src="{{ $url }}"></video>
                @endif
            </div>
        @elseif($vlog->image_media_id)
            <img src="{{ route('media.show', $vlog->image_media_id) }}" alt="{{ $vlog->title }}" class="img-fluid rounded-4 my-4 shadow-lg" style="width:100%;max-height:520px;object-fit:cover">
        @endif

        @if($vlog->description)
            <p class="lead text-white-75">{{ $vlog->description }}</p>
        @endif
        @if($vlog->body)
            <div class="vlog-body text-white-75">{!! nl2br(e($vlog->body)) !!}</div>
        @endif
    </div>

    @if($related->count() > 0)
        <div class="container pb-5">
            <h3 class="text-white mb-4">More From The Club</h3>
            <div class="row g-4">
                @foreach($related as $r)
                    <div class="col-md-6 col-lg-3">
                        <a href="{{ route('vlogs.show', $r->slug) }}" class="vlog-card glass-card d-block h-100 text-decoration-none overflow-hidden">
                            <div class="vlog-media vlog-media-sm">
                                @if($r->image_media_id)
                                    <img src="{{ route('media.thumb', $r->image_media_id) }}" alt="{{ $r->title }}" loading="lazy">
                                @else
                                    <div class="vlog-fallback"><i class="bi bi-play-btn"></i></div>
                                @endif
                            </div>
                            <div class="p-3">
                                <h6 class="text-white mb-1">{{ $r->title }}</h6>
                                <small class="text-warning">{{ optional($r->published_at)->format('d M Y') }}</small>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</article>
@endsection
