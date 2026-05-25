@extends('layouts.admin')

@section('content')
@php
    $menuIconOptions = [
        'bi-stars' => 'Stars',
        'bi-journal-richtext' => 'Article',
        'bi-trophy-fill' => 'Trophy',
        'bi-people-fill' => 'People',
        'bi-camera-reels' => 'Camera',
        'bi-info-circle-fill' => 'Info',
        'bi-award-fill' => 'Award',
        'bi-geo-alt-fill' => 'Location',
        'bi-image-fill' => 'Image',
        'bi-link-45deg' => 'Link',
    ];
@endphp
<div class="glass-card p-4 mb-4">
    <form method="POST" action="{{ route('admin.menus.store') }}" class="row g-3">
        @csrf
        <div class="col-md-4"><input name="title" class="form-control" placeholder="Menu title" required></div>
        <div class="col-md-2"><select name="type" class="form-select"><option value="internal">Internal</option><option value="external">External</option></select></div>
        <div class="col-md-3"><input name="url" class="form-control" placeholder="URL"></div>
        <div class="col-md-2">
            <input name="icon" class="form-control" list="menu-icons" placeholder="bi-stars">
            <datalist id="menu-icons">
                @foreach($menuIconOptions as $iconClass => $label)
                    <option value="{{ $iconClass }}">{{ $label }}</option>
                @endforeach
            </datalist>
        </div>
        <div class="col-md-2"><input name="sort_order" type="number" class="form-control" placeholder="Order"></div>
        <div class="col-md-1 d-grid"><button class="btn btn-gold">Add</button></div>
    </form>
</div>

<div class="glass-card p-4">
    <table class="table table-dark table-hover align-middle">
        <thead><tr><th>Title</th><th>Icon</th><th>Type</th><th>URL</th><th>Actions</th></tr></thead>
        <tbody>
            @foreach($menus as $menu)
                <tr>
                    <td>{{ $menu->title }}</td>
                    <td>
                        @if($menu->icon)
                            <span class="badge text-bg-secondary"><i class="bi {{ $menu->icon }} me-1"></i>{{ $menu->icon }}</span>
                        @else
                            <span class="text-light-emphasis">-</span>
                        @endif
                    </td>
                    <td>{{ $menu->type }}</td><td>{{ $menu->url }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $menus->links() }}
</div>
@endsection
