@extends('layouts.admin')

@section('content')
<div class="glass-card p-4 mb-4">
    <form method="POST" action="{{ route('admin.achievements.store') }}" class="row g-3">
        @csrf
        <div class="col-md-3"><input name="title" class="form-control" placeholder="Achievement title" required></div>
        <div class="col-md-2"><input name="year" class="form-control" placeholder="Year"></div>
        <div class="col-md-3"><input name="badge_color" class="form-control" placeholder="#D4AF37"></div>
        <div class="col-md-3"><input name="media_library_id" class="form-control" placeholder="Media ID"></div>
        <div class="col-md-1 d-grid"><button class="btn btn-gold">Add</button></div>
        <div class="col-12"><textarea name="description" class="form-control" placeholder="Description"></textarea></div>
    </form>
</div>
<div class="glass-card p-4"><table class="table table-dark table-hover"><thead><tr><th>Title</th><th>Year</th><th></th></tr></thead><tbody>@foreach($achievements as $item)<tr><td>{{ $item->title }}</td><td>{{ $item->year }}</td><td><form method="POST" action="{{ route('admin.achievements.destroy', $item) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>@endforeach</tbody></table>{{ $achievements->links() }}</div>
@endsection
