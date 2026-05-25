@extends('layouts.admin')

@section('content')
<div class="glass-card p-4 mb-4">
    <form method="POST" action="{{ route('admin.sponsors.store') }}" class="row g-3">
        @csrf
        <div class="col-md-3"><input name="name" class="form-control" placeholder="Sponsor name" required></div>
        <div class="col-md-3"><input name="tier" class="form-control" placeholder="Tier"></div>
        <div class="col-md-4"><input name="website_url" class="form-control" placeholder="Website"></div>
        <div class="col-md-2 d-grid"><button class="btn btn-gold">Add</button></div>
    </form>
</div>
<div class="glass-card p-4"><table class="table table-dark table-hover"><thead><tr><th>Name</th><th>Tier</th><th>URL</th><th></th></tr></thead><tbody>@foreach($sponsors as $sponsor)<tr><td>{{ $sponsor->name }}</td><td>{{ $sponsor->tier }}</td><td>{{ $sponsor->website_url }}</td><td><form method="POST" action="{{ route('admin.sponsors.destroy', $sponsor) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>@endforeach</tbody></table>{{ $sponsors->links() }}</div>
@endsection
