@extends('layouts.admin')

@section('content')
<div class="glass-card p-4 mb-4">
    <form method="POST" action="{{ route('admin.media-library.store') }}" enctype="multipart/form-data" class="row g-3">
        @csrf
        <div class="col-md-4"><input class="form-control" name="module" placeholder="Module (gallery, slider, etc)"></div>
        <div class="col-md-6"><input type="file" class="form-control" name="image" required></div>
        <div class="col-md-2 d-grid"><button class="btn btn-gold">Upload</button></div>
    </form>
</div>
<div class="row g-3">
    @foreach($mediaItems as $media)
        <div class="col-6 col-md-4 col-xl-2">
            <div class="glass-card p-2">
                <img class="w-100 rounded mb-2" src="{{ route('media.thumb', $media->id) }}" alt="media">
                <small class="d-block text-truncate">{{ $media->original_name }}</small>
                <form method="POST" action="{{ route('admin.media-library.destroy', $media) }}" class="mt-2">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger w-100">Delete</button></form>
            </div>
        </div>
    @endforeach
</div>
<div class="mt-3">{{ $mediaItems->links() }}</div>
@endsection
