@extends('layouts.admin')

@section('content')
<div class="glass-card p-4 mb-4">
    <form method="POST" action="{{ route('admin.submenus.store') }}" class="row g-3">
        @csrf
        <div class="col-md-3"><select name="menu_id" class="form-select">@foreach($menus as $menu)<option value="{{ $menu->id }}">{{ $menu->title }}</option>@endforeach</select></div>
        <div class="col-md-3"><input name="title" class="form-control" placeholder="Submenu title" required></div>
        <div class="col-md-2"><input name="slug" class="form-control" placeholder="slug" required></div>
        <div class="col-md-3"><input name="url" class="form-control" placeholder="URL"></div>
        <div class="col-md-1 d-grid"><button class="btn btn-gold">Add</button></div>
    </form>
</div>
<div class="glass-card p-4">
    <table class="table table-dark table-hover"><thead><tr><th>Menu</th><th>Title</th><th>URL</th><th></th></tr></thead><tbody>@foreach($submenus as $submenu)<tr><td>{{ $submenu->menu?->title }}</td><td>{{ $submenu->title }}</td><td>{{ $submenu->url }}</td><td><form method="POST" action="{{ route('admin.submenus.destroy', $submenu) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>@endforeach</tbody></table>
    {{ $submenus->links() }}
</div>
@endsection
