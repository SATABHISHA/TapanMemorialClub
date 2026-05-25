@extends('layouts.admin')

@section('content')
<div class="glass-card p-4 mb-4">
    <h5 class="mb-3 text-white"><i class="bi bi-newspaper"></i> Add Blog</h5>
    <form method="POST" action="{{ route('admin.blogs.store') }}" class="row g-3">
        @csrf
        <div class="col-md-4"><input name="title" class="form-control" placeholder="Blog title" required></div>
        <div class="col-md-3"><input name="youtube_url" class="form-control" placeholder="YouTube URL"></div>
        <div class="col-md-2"><select name="status" class="form-select"><option value="draft">Draft</option><option value="published" selected>Published</option></select></div>
        <div class="col-md-3"><input name="published_at" type="datetime-local" class="form-control"></div>
        <div class="col-12"><textarea name="excerpt" class="form-control" placeholder="Excerpt"></textarea></div>
        <div class="col-12"><textarea name="content" class="form-control" rows="4" placeholder="Content" required></textarea></div>
        <div class="col-12 d-grid"><button class="btn btn-gold"><i class="bi bi-plus-lg"></i> Publish</button></div>
    </form>
</div>

<div class="glass-card p-4">
    <h5 class="mb-3 text-white">Blogs</h5>
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Published</th>
                    <th class="text-end" style="min-width:320px">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($blogs as $blog)
                <tr>
                    <td>
                        <strong>{{ $blog->title }}</strong>
                        <div class="small text-white-50">/{{ $blog->slug }}</div>
                    </td>
                    <td>
                        <span class="badge {{ $blog->status === 'published' ? 'bg-success' : 'bg-warning text-dark' }}">{{ ucfirst($blog->status) }}</span>
                    </td>
                    <td class="small">{{ optional($blog->published_at)->format('d M Y H:i') ?: '—' }}</td>
                    <td class="text-end">
                        <form method="POST" action="{{ route('admin.blogs.update', $blog) }}" class="d-inline">
                            @csrf @method('PATCH')
                            <input type="hidden" name="title" value="{{ $blog->title }}">
                            <input type="hidden" name="content" value="{{ $blog->content }}">
                            <input type="hidden" name="excerpt" value="{{ $blog->excerpt }}">
                            <input type="hidden" name="youtube_url" value="{{ $blog->youtube_url }}">
                            <input type="hidden" name="thumbnail_media_id" value="{{ $blog->thumbnail_media_id }}">
                            <input type="hidden" name="status" value="{{ $blog->status === 'published' ? 'draft' : 'published' }}">
                            @if($blog->status === 'published')
                                <button class="btn btn-sm btn-outline-warning" title="Move to Draft"><i class="bi bi-arrow-down-circle"></i> Draft</button>
                            @else
                                <button class="btn btn-sm btn-outline-success" title="Publish Now"><i class="bi bi-arrow-up-circle"></i> Publish</button>
                            @endif
                        </form>

                        @if($blog->status === 'published')
                            <a href="{{ route('blogs.show', $blog->slug) }}" target="_blank" class="btn btn-sm btn-outline-info" title="Preview"><i class="bi bi-eye"></i> Preview</a>
                        @else
                            <button class="btn btn-sm btn-outline-secondary" disabled title="Publish first"><i class="bi bi-eye-slash"></i></button>
                        @endif

                        <button class="btn btn-sm btn-outline-light" data-bs-toggle="modal" data-bs-target="#editBlog{{ $blog->id }}"><i class="bi bi-pencil"></i> Edit</button>

                        <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}" class="d-inline" onsubmit="return confirm('Delete this blog?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $blogs->links() }}
</div>

@foreach($blogs as $blog)
    <div class="modal fade" id="editBlog{{ $blog->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" style="background:#0e1932;color:#fff;border:1px solid rgba(212,175,55,.3)">
                <form method="POST" action="{{ route('admin.blogs.update', $blog) }}">
                    @csrf @method('PATCH')
                    <div class="modal-header" style="border-color:rgba(212,175,55,.2)">
                        <h5 class="modal-title"><i class="bi bi-pencil-square text-warning"></i> Edit Blog</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-8"><label class="small text-white-50">Title</label><input name="title" value="{{ $blog->title }}" class="form-control" required></div>
                            <div class="col-md-4"><label class="small text-white-50">Status</label>
                                <select name="status" class="form-select">
                                    <option value="draft" @selected($blog->status === 'draft')>Draft</option>
                                    <option value="published" @selected($blog->status === 'published')>Published</option>
                                </select>
                            </div>
                            <div class="col-md-8"><label class="small text-white-50">YouTube URL</label><input name="youtube_url" value="{{ $blog->youtube_url }}" class="form-control"></div>
                            <div class="col-md-4"><label class="small text-white-50">Published At</label><input name="published_at" type="datetime-local" value="{{ optional($blog->published_at)->format('Y-m-d\TH:i') }}" class="form-control"></div>
                            <div class="col-12"><label class="small text-white-50">Excerpt</label><textarea name="excerpt" class="form-control" rows="2">{{ $blog->excerpt }}</textarea></div>
                            <div class="col-12"><label class="small text-white-50">Content</label><textarea name="content" class="form-control" rows="8" required>{{ $blog->content }}</textarea></div>
                            <input type="hidden" name="thumbnail_media_id" value="{{ $blog->thumbnail_media_id }}">
                        </div>
                    </div>
                    <div class="modal-footer" style="border-color:rgba(212,175,55,.2)">
                        @if($blog->status === 'published')
                            <a href="{{ route('blogs.show', $blog->slug) }}" target="_blank" class="btn btn-outline-info me-auto"><i class="bi bi-eye"></i> Preview Live</a>
                        @endif
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-gold"><i class="bi bi-check-lg"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection
