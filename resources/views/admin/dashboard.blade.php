@extends('layouts.admin')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4"><div class="glass-card p-4"><h3>{{ $stats['performances'] }}</h3><p>Performances</p></div></div>
    <div class="col-md-4"><div class="glass-card p-4"><h3>{{ $stats['blogs'] }}</h3><p>Blogs</p></div></div>
    <div class="col-md-4"><div class="glass-card p-4"><h3>{{ $stats['media'] }}</h3><p>Media Files</p></div></div>
    <div class="col-md-6"><div class="glass-card p-4"><h3>{{ $stats['contacts'] }}</h3><p>Contact Messages</p></div></div>
    <div class="col-md-6"><div class="glass-card p-4"><h3>{{ $stats['visits'] }}</h3><p>Total Visits</p></div></div>
</div>

<div class="glass-card p-4">
    <h5>Recent Uploads</h5>
    <div class="row g-3">
        @foreach($recentMedia as $media)
            <div class="col-6 col-md-3">
                <img class="w-100 rounded" src="{{ route('media.thumb', $media->id) }}" alt="media">
            </div>
        @endforeach
    </div>
</div>
@endsection
