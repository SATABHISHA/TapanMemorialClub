@extends('layouts.admin')

@section('content')
<div class="glass-card p-4 mb-4">
    <h6 class="text-gold mb-3">Add Achievement</h6>
    <form method="POST" action="{{ route('admin.achievements.store') }}" class="row g-3">
        @csrf
        <div class="col-md-3">
            <input name="title" class="form-control" placeholder="Achievement title" value="{{ old('title') }}" required>
        </div>
        <div class="col-md-2">
            <input name="year" class="form-control" placeholder="Year" type="number" value="{{ old('year') }}">
        </div>
        <div class="col-md-2">
            <input name="badge_color" class="form-control" placeholder="#D4AF37" value="{{ old('badge_color') }}">
        </div>
        <div class="col-md-2">
            <input name="media_library_id" class="form-control" placeholder="Media ID" type="number" value="{{ old('media_library_id') }}">
        </div>
        <div class="col-md-2">
            <input name="sort_order" class="form-control" placeholder="Order" type="number" value="{{ old('sort_order', 0) }}">
        </div>
        <div class="col-md-1 d-grid">
            <button class="btn btn-gold">Add</button>
        </div>
        <div class="col-12">
            <textarea name="description" class="form-control" placeholder="Description">{{ old('description') }}</textarea>
        </div>
    </form>
    @if ($errors->any())
        <div class="alert alert-danger mt-3 mb-0">{{ $errors->first() }}</div>
    @endif
</div>

<div class="glass-card p-4">
    <table class="table table-dark table-hover align-middle mb-0">
        <thead>
            <tr>
                <th width="90">Image</th>
                <th>Title</th>
                <th width="90">Year</th>
                <th width="90">Order</th>
                <th width="120">Media ID</th>
                <th class="text-end" width="170">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($achievements as $item)
                <tr>
                    <td>
                        @if($item->media_library_id)
                            <img
                                src="{{ route('media.thumb', $item->media_library_id) }}"
                                alt="{{ $item->title }}"
                                class="rounded"
                                style="width:64px;height:46px;object-fit:cover;cursor:pointer;"
                                onclick="previewAchievement('{{ route('media.show', $item->media_library_id) }}', '{{ addslashes($item->title) }}')"
                            >
                        @else
                            <div class="rounded bg-secondary d-flex align-items-center justify-content-center" style="width:64px;height:46px;">
                                <i class="bi bi-image text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->year ?? '—' }}</td>
                    <td>{{ $item->sort_order ?? 0 }}</td>
                    <td>{{ $item->media_library_id ?? '—' }}</td>
                    <td class="text-end">
                        <div class="d-flex gap-2 justify-content-end">
                            @if($item->media_library_id)
                                <button
                                    class="btn btn-sm btn-outline-info"
                                    title="Preview"
                                    onclick="previewAchievement('{{ route('media.show', $item->media_library_id) }}', '{{ addslashes($item->title) }}')"
                                >
                                    <i class="bi bi-eye"></i>
                                </button>
                            @endif

                            <button
                                class="btn btn-sm btn-outline-warning"
                                title="Edit"
                                onclick='openAchievementEdit(@json([
                                    "id" => $item->id,
                                    "title" => $item->title,
                                    "description" => $item->description,
                                    "year" => $item->year,
                                    "badge_color" => $item->badge_color,
                                    "media_library_id" => $item->media_library_id,
                                    "sort_order" => $item->sort_order,
                                    "is_featured" => (bool) $item->is_featured,
                                    "thumb_url" => $item->media_library_id ? route("media.thumb", $item->media_library_id) : null,
                                ]))'
                            >
                                <i class="bi bi-pencil"></i>
                            </button>

                            <form method="POST" action="{{ route('admin.achievements.destroy', $item) }}" onsubmit="return confirm('Delete this achievement?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No achievements found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-3">{{ $achievements->links() }}</div>
</div>

<div class="modal fade" id="achievementPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark border-0">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white" id="achievementPreviewTitle">Achievement Preview</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-3">
                <img id="achievementPreviewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height:75vh;">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editAchievementModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white">Edit Achievement</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="achievementEditForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div id="achievementCurrentImgWrap" class="text-center mb-3" style="display:none;">
                        <img id="achievementCurrentImg" src="" alt="Current" class="rounded" style="max-height:110px;">
                        <p class="text-muted small mt-1 mb-0">Current image</p>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label text-muted small">Title</label>
                            <input type="text" name="title" id="editAchievementTitle" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small">Year</label>
                            <input type="number" name="year" id="editAchievementYear" class="form-control" placeholder="Year">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small">Badge Color</label>
                            <input type="text" name="badge_color" id="editAchievementBadgeColor" class="form-control" placeholder="#D4AF37">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small">Media ID</label>
                            <input type="number" name="media_library_id" id="editAchievementMediaId" class="form-control" placeholder="Media ID">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small">Display Order</label>
                            <input type="number" name="sort_order" id="editAchievementSortOrder" class="form-control" placeholder="0">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-muted small">Description</label>
                            <textarea name="description" id="editAchievementDescription" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="editAchievementFeatured" value="1">
                                <label class="form-check-label text-muted" for="editAchievementFeatured">Featured</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gold">Update Achievement</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewAchievement(url, title) {
    document.getElementById('achievementPreviewImg').src = url;
    document.getElementById('achievementPreviewTitle').textContent = title || 'Achievement Preview';
    new bootstrap.Modal(document.getElementById('achievementPreviewModal')).show();
}

function openAchievementEdit(payload) {
    document.getElementById('achievementEditForm').action = '/admin/achievements/' + payload.id;
    document.getElementById('editAchievementTitle').value = payload.title || '';
    document.getElementById('editAchievementDescription').value = payload.description || '';
    document.getElementById('editAchievementYear').value = Number.isFinite(payload.year) ? payload.year : '';
    document.getElementById('editAchievementBadgeColor').value = payload.badge_color || '#D4AF37';
    document.getElementById('editAchievementMediaId').value = Number.isFinite(payload.media_library_id) ? payload.media_library_id : '';
    document.getElementById('editAchievementSortOrder').value = Number.isFinite(payload.sort_order) ? payload.sort_order : 0;
    document.getElementById('editAchievementFeatured').checked = !!payload.is_featured;

    const wrap = document.getElementById('achievementCurrentImgWrap');
    const image = document.getElementById('achievementCurrentImg');
    if (payload.thumb_url) {
        image.src = payload.thumb_url;
        wrap.style.display = 'block';
    } else {
        image.src = '';
        wrap.style.display = 'none';
    }

    new bootstrap.Modal(document.getElementById('editAchievementModal')).show();
}
</script>
@endpush
@endsection
