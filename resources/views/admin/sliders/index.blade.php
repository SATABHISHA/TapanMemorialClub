@extends('layouts.admin')

@section('content')
<div class="glass-card p-4 mb-4">
    <form method="POST" action="{{ route('admin.sliders.store') }}" class="row g-3">
        @csrf
        <div class="col-md-3"><input name="title" class="form-control" placeholder="Title" required></div>
        <div class="col-md-3"><input name="subtitle" class="form-control" placeholder="Subtitle"></div>
        <div class="col-md-3"><input name="media_library_id" class="form-control" placeholder="Media ID"></div>
        <div class="col-md-2"><input name="cta_link" class="form-control" placeholder="CTA Link"></div>
        <div class="col-md-1 d-grid"><button class="btn btn-gold">Add</button></div>
    </form>
</div>
<div class="glass-card p-4"><table class="table table-dark table-hover"><thead><tr><th>Title</th><th>Media ID</th><th></th></tr></thead><tbody>@foreach($sliders as $slider)<tr><td>{{ $slider->title }}</td><td>{{ $slider->media_library_id }}</td><td><form method="POST" action="{{ route('admin.sliders.destroy', $slider) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>@endforeach</tbody></table>{{ $sliders->links() }}</div>
@endsection
