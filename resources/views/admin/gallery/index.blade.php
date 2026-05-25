@extends('layouts.admin')

@section('content')
<div class="glass-card p-4 mb-4">
    <form method="POST" action="{{ route('admin.gallery.store') }}" class="row g-3">
        @csrf
        <div class="col-md-4"><input name="title" class="form-control" placeholder="Title"></div>
        <div class="col-md-3"><input name="category" class="form-control" placeholder="Category"></div>
        <div class="col-md-3"><input name="media_library_id" class="form-control" placeholder="Media ID"></div>
        <div class="col-md-2 d-grid"><button class="btn btn-gold">Add</button></div>
    </form>
</div>
<div class="glass-card p-4"><table class="table table-dark table-hover"><thead><tr><th>Title</th><th>Category</th><th>Media</th><th></th></tr></thead><tbody>@foreach($galleryItems as $item)<tr><td>{{ $item->title }}</td><td>{{ $item->category }}</td><td>{{ $item->media_library_id }}</td><td><form method="POST" action="{{ route('admin.gallery.destroy', $item) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>@endforeach</tbody></table>{{ $galleryItems->links() }}</div>
@endsection
