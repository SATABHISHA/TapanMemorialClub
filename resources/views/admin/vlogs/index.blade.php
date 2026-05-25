@extends('layouts.admin')

@section('content')
<div class="glass-card p-4 mb-4">
    <h5 class="mb-3 text-white"><i class="bi bi-camera-reels"></i> Add Vlog</h5>
    <form method="POST" action="{{ route('admin.vlogs.store') }}" enctype="multipart/form-data" class="row g-3">
        @csrf
        <div class="col-md-6"><input name="title" class="form-control" placeholder="Vlog title" required></div>
        <div class="col-md-3"><input name="slug" class="form-control" placeholder="Slug (auto)"></div>
        <div class="col-md-3"><input name="video_url" class="form-control" placeholder="YouTube / Video URL"></div>

        <div class="col-md-4">
            <label class="form-label small text-white-50">Attach to Menu</label>
            <select name="menu_id" class="form-select">
                <option value="">— None —</option>
                @foreach($menus as $menu)
                    <option value="{{ $menu->id }}">{{ $menu->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label small text-white-50">Attach to Submenu</label>
            <select name="submenu_id" class="form-select">
                <option value="">— None —</option>
                @foreach($submenus as $sm)
                    <option value="{{ $sm->id }}">{{ optional($sm->menu)->title }} › {{ $sm->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small text-white-50">Status</label>
            <select name="status" class="form-select">
                <option value="published">Published</option>
                <option value="draft">Draft</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small text-white-50">Featured</label>
            <select name="is_featured" class="form-select">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label small text-white-50">Image (stored as bytes in DB)</label>
            <input name="image" type="file" accept="image/*" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label small text-white-50">Published at</label>
            <input name="published_at" type="datetime-local" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label small text-white-50">Sort order</label>
            <input name="sort_order" type="number" value="0" class="form-control">
        </div>

        <div class="col-12"><textarea name="description" class="form-control" rows="2" placeholder="Short description / excerpt" maxlength="1000"></textarea></div>
        <div class="col-12"><textarea name="body" class="form-control" rows="6" placeholder="Full vlog body (optional)"></textarea></div>
        <div class="col-12 d-grid"><button class="btn btn-gold"><i class="bi bi-plus-lg"></i> Save Vlog</button></div>
    </form>
</div>

<div class="glass-card p-4">
    <h5 class="mb-3 text-white">Vlogs</h5>
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle">
            <thead>
                <tr>
                    <th style="width:80px">Image</th>
                    <th>Title</th>
                    <th>Menu / Submenu</th>
                    <th>Status</th>
                    <th>Published</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($vlogs as $vlog)
                <tr>
                    <td>
                        @if($vlog->image_media_id)
                            <img src="{{ route('media.thumb', $vlog->image_media_id) }}" alt="" style="width:64px;height:48px;object-fit:cover;border-radius:.4rem">
                        @else
                            <span class="badge bg-secondary">No image</span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $vlog->title }}</strong>
                        <div class="small text-white-50">/{{ $vlog->slug }}</div>
                    </td>
                    <td class="small">
                        {{ optional($vlog->menu)->title ?: '—' }}
                        @if($vlog->submenu) <span class="text-white-50">› {{ $vlog->submenu->title }}</span>@endif
                    </td>
                    <td>
                        <span class="badge {{ $vlog->status === 'published' ? 'bg-success' : 'bg-warning' }}">{{ ucfirst($vlog->status) }}</span>
                        @if($vlog->is_featured)<span class="badge bg-warning text-dark ms-1">Featured</span>@endif
                    </td>
                    <td class="small">{{ optional($vlog->published_at)->format('d M Y H:i') }}</td>
                    <td class="text-end">
                        <a href="{{ route('vlogs.show', $vlog->slug) }}" target="_blank" class="btn btn-sm btn-outline-light"><i class="bi bi-eye"></i></a>
                        <form method="POST" action="{{ route('admin.vlogs.destroy', $vlog) }}" class="d-inline" onsubmit="return confirm('Delete this vlog?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-white-50 py-4">No vlogs yet. Add one above.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $vlogs->links() }}
</div>
@endsection
