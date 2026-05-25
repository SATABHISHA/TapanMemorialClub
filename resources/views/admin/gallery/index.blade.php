@extends('layouts.admin')

@section('content')

{{-- Add Gallery Item --}}
<div class="glass-card p-4 mb-4">
    <h6 class="text-gold mb-3">Add Gallery Item</h6>
    <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data" class="row g-3">
        @csrf
        <div class="col-md-3">
            <input name="title" class="form-control" placeholder="Title" value="{{ old('title') }}">
        </div>
        <div class="col-md-2">
            <input name="category" class="form-control" placeholder="Category" value="{{ old('category') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label text-muted small mb-1">Upload Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <div class="col-md-2">
            <label class="form-label text-muted small mb-1">Or Media ID</label>
            <input name="media_library_id" class="form-control" placeholder="Media ID" type="number">
        </div>
        <div class="col-md-2 d-grid align-self-end">
            <button class="btn btn-gold">Add</button>
        </div>
    </form>
    @if ($errors->any())
        <div class="alert alert-danger mt-3 mb-0">{{ $errors->first() }}</div>
    @endif
    @if (session('status'))
        <div class="alert alert-success mt-3 mb-0">{{ session('status') }}</div>
    @endif
</div>

{{-- Gallery Table --}}
<div class="glass-card p-4">
    <table class="table table-dark table-hover align-middle mb-0">
        <thead>
            <tr>
                <th width="70">Image</th>
                <th>Title</th>
                <th>Category</th>
                <th>Media ID</th>
                <th>Featured</th>
                <th>Order</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($galleryItems as $item)
            <tr>
                <td>
                    @if($item->media_library_id)
                        <img src="{{ route('media.thumb', $item->media_library_id) }}"
                             alt="{{ $item->title }}"
                             class="rounded"
                             style="width:56px;height:42px;object-fit:cover;cursor:pointer;"
                             onclick="previewImage('{{ route('media.show', $item->media_library_id) }}', '{{ addslashes($item->title ?? '') }}')"
                             title="Click to preview">
                    @else
                        <div class="rounded bg-secondary d-flex align-items-center justify-content-center" style="width:56px;height:42px;">
                            <i class="bi bi-image text-muted"></i>
                        </div>
                    @endif
                </td>
                <td>{{ $item->title }}</td>
                <td><span class="badge bg-secondary">{{ $item->category }}</span></td>
                <td>{{ $item->media_library_id ?? '—' }}</td>
                <td>
                    @if($item->is_featured)
                        <span class="badge bg-warning text-dark">Featured</span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>{{ $item->display_order ?? '—' }}</td>
                <td class="text-end">
                    <div class="d-flex gap-2 justify-content-end">
                        {{-- Preview --}}
                        @if($item->media_library_id)
                        <button class="btn btn-sm btn-outline-info"
                                onclick="previewImage('{{ route('media.show', $item->media_library_id) }}', '{{ addslashes($item->title ?? '') }}')"
                                title="Preview">
                            <i class="bi bi-eye"></i>
                        </button>
                        @endif

                        {{-- Edit --}}
                        <button class="btn btn-sm btn-outline-warning"
                                onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->title ?? '') }}', '{{ addslashes($item->category ?? '') }}', {{ $item->media_library_id ?? 'null' }}, {{ $item->is_featured ? 'true' : 'false' }}, {{ $item->display_order ?? 0 }}, {{ $item->media_library_id ? "'".route('media.thumb', $item->media_library_id)."'" : 'null' }})"
                                title="Edit">
                            <i class="bi bi-pencil"></i>
                        </button>

                        {{-- Delete --}}
                        <form method="POST" action="{{ route('admin.gallery.destroy', $item) }}" onsubmit="return confirm('Delete this gallery item?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-3">{{ $galleryItems->links() }}</div>
</div>

{{-- Preview Modal --}}
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark border-0">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white" id="previewModalTitle">Image Preview</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-3">
                <img id="previewModalImg" src="" alt="Preview" class="img-fluid rounded" style="max-height:75vh;">
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white">Edit Gallery Item</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body">
                    {{-- Current image preview --}}
                    <div id="editCurrentImg" class="text-center mb-3" style="display:none!important;">
                        <img id="editThumb" src="" alt="" class="rounded" style="max-height:120px;">
                        <p class="text-muted small mt-1 mb-0">Current image</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small">Replace with new image (optional)</label>
                        <input type="file" name="image" class="form-control" accept="image/*" id="editImageInput">
                        <div id="editImagePreview" class="mt-2" style="display:none;">
                            <img id="editImagePreviewImg" src="" alt="" class="rounded" style="max-height:100px;">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Or Media ID</label>
                        <input type="number" name="media_library_id" id="editMediaId" class="form-control" placeholder="Media Library ID">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Title</label>
                        <input type="text" name="title" id="editTitle" class="form-control" placeholder="Title">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Category</label>
                        <input type="text" name="category" id="editCategory" class="form-control" placeholder="Category">
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label text-muted small">Display Order</label>
                            <input type="number" name="display_order" id="editDisplayOrder" class="form-control" placeholder="0">
                        </div>
                        <div class="col-6 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="editIsFeatured" value="1">
                                <label class="form-check-label text-muted" for="editIsFeatured">Featured</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gold">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewImage(url, title) {
    document.getElementById('previewModalImg').src = url;
    document.getElementById('previewModalTitle').textContent = title || 'Image Preview';
    new bootstrap.Modal(document.getElementById('previewModal')).show();
}

function openEditModal(id, title, category, mediaId, isFeatured, displayOrder, thumbUrl) {
    document.getElementById('editForm').action = '/admin/gallery/' + id;
    document.getElementById('editTitle').value = title;
    document.getElementById('editCategory').value = category;
    document.getElementById('editMediaId').value = mediaId ?? '';
    document.getElementById('editDisplayOrder').value = displayOrder || '';
    document.getElementById('editIsFeatured').checked = isFeatured;
    document.getElementById('editImageInput').value = '';
    document.getElementById('editImagePreview').style.display = 'none';

    const thumbWrap = document.getElementById('editCurrentImg');
    const thumb = document.getElementById('editThumb');
    if (thumbUrl) {
        thumb.src = thumbUrl;
        thumbWrap.style.cssText = '';
    } else {
        thumbWrap.style.cssText = 'display:none!important';
    }

    new bootstrap.Modal(document.getElementById('editModal')).show();
}

// Live preview of newly selected file
document.getElementById('editImageInput').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) { document.getElementById('editImagePreview').style.display = 'none'; return; }
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('editImagePreviewImg').src = e.target.result;
        document.getElementById('editImagePreview').style.display = 'block';
    };
    reader.readAsDataURL(file);
});
</script>
@endpush

@endsection

