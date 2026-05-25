@extends('layouts.admin')

@section('content')
<div class="glass-card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
        <div>
            <h4 class="mb-1 text-warning">Create Dynamic Page</h4>
            <p class="text-light-emphasis small mb-0">Create a rich content page, optionally upload a hero image, and auto-publish it into the main menu. Page images are optimized and stored as bytes.</p>
        </div>
        <span class="badge text-bg-warning">Large images over 5MB are compressed below 5MB automatically</span>
    </div>

    <form method="POST" action="{{ route('admin.dynamic-pages.store') }}" enctype="multipart/form-data" class="row g-3">
        @csrf
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
        <div class="col-lg-6">
            <label class="form-label">Page Title</label>
            <input name="title" class="form-control" placeholder="Tournament Legacy" required>
        </div>
        <div class="col-lg-3">
            <label class="form-label">Page Order</label>
            <input name="sort_order" type="number" class="form-control" value="0" min="0">
        </div>
        <div class="col-lg-3">
            <label class="form-label">Menu Order</label>
            <input name="menu_sort_order" type="number" class="form-control" value="0" min="0">
        </div>
        <div class="col-lg-8">
            <label class="form-label">Description</label>
            <textarea name="description" rows="3" class="form-control" placeholder="Short intro shown on cards and page hero."></textarea>
        </div>
        <div class="col-lg-4">
            <label class="form-label">Hero Image</label>
            <input name="image" type="file" class="form-control" accept="image/*">
        </div>
        <div class="col-12">
            <label class="form-label">Page Content</label>
            <textarea name="content" rows="10" class="form-control" placeholder="Write the full page content here..." required></textarea>
        </div>
        <div class="col-lg-4">
            <label class="form-label">Menu Title</label>
            <input name="menu_title" class="form-control" placeholder="Defaults to page title">
        </div>
        <div class="col-lg-4">
            <label class="form-label">Menu Icon</label>
            <input name="menu_icon" class="form-control" list="menu-icon-options" placeholder="bi-stars">
            <datalist id="menu-icon-options">
                @foreach($menuIconOptions as $iconClass => $label)
                    <option value="{{ $iconClass }}">{{ $label }}</option>
                @endforeach
            </datalist>
            <small class="text-light-emphasis">Use a Bootstrap Icon class like <span class="text-warning">bi-stars</span>. No image upload is needed.</small>
        </div>
        <div class="col-lg-4 d-flex align-items-end gap-3 flex-wrap">
            <div class="form-check form-switch">
                <input type="hidden" name="is_published" value="0">
                <input class="form-check-input" type="checkbox" name="is_published" value="1" id="create-page-published" checked>
                <label class="form-check-label" for="create-page-published">Published</label>
            </div>
            <div class="form-check form-switch">
                <input type="hidden" name="show_on_home" value="0">
                <input class="form-check-input" type="checkbox" name="show_on_home" value="1" id="create-page-home" checked>
                <label class="form-check-label" for="create-page-home">Show On Home</label>
            </div>
            <div class="form-check form-switch">
                <input type="hidden" name="menu_is_active" value="0">
                <input class="form-check-input" type="checkbox" name="menu_is_active" value="1" id="create-page-menu" checked>
                <label class="form-check-label" for="create-page-menu">Menu Active</label>
            </div>
        </div>
        <div class="col-12">
            <button class="btn btn-gold">Create Page & Menu</button>
        </div>
    </form>
</div>

<div class="glass-card p-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h5 class="mb-0 text-info">Existing Dynamic Pages</h5>
        <span class="small text-light-emphasis">Edit content, image, publication, and menu metadata from one place.</span>
    </div>

    <div class="d-grid gap-4">
        @forelse($pages as $page)
            <article class="glass-card p-4 border border-warning-subtle">
                <div class="row g-4 align-items-start">
                    <div class="col-xl-3">
                        <div class="rounded overflow-hidden border border-secondary-subtle bg-dark-subtle">
                            @if($page->media_library_id)
                                <img src="{{ route('media.thumb', $page->media_library_id) }}" alt="{{ $page->title }}" class="w-100" style="aspect-ratio: 4/3; object-fit: cover; display: block;">
                            @else
                                <div class="d-grid place-items-center text-warning" style="aspect-ratio: 4/3; display:grid;"><i class="bi bi-journal-text fs-1"></i></div>
                            @endif
                        </div>
                        <div class="small text-light-emphasis mt-2">
                            <div><strong class="text-warning">Slug:</strong> {{ $page->slug }}</div>
                            <div><strong class="text-warning">Views:</strong> {{ $page->view_count }}</div>
                            <div><strong class="text-warning">Menu:</strong> {{ $page->menu?->title ?: 'Not linked' }}</div>
                        </div>
                    </div>
                    <div class="col-xl-9">
                        <form method="POST" action="{{ route('admin.dynamic-pages.update', $page) }}" enctype="multipart/form-data" class="row g-3">
                            @csrf
                            @method('PUT')
                            <div class="col-lg-6">
                                <label class="form-label">Page Title</label>
                                <input name="title" class="form-control" value="{{ $page->title }}" required>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label">Page Order</label>
                                <input name="sort_order" type="number" class="form-control" value="{{ $page->sort_order }}" min="0">
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label">Menu Order</label>
                                <input name="menu_sort_order" type="number" class="form-control" value="{{ $page->menu?->sort_order ?? $page->sort_order }}" min="0">
                            </div>
                            <div class="col-lg-8">
                                <label class="form-label">Description</label>
                                <textarea name="description" rows="3" class="form-control">{{ $page->description }}</textarea>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Replace Hero Image</label>
                                <input name="image" type="file" class="form-control" accept="image/*">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Page Content</label>
                                <textarea name="content" rows="9" class="form-control" required>{{ $page->content }}</textarea>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Menu Title</label>
                                <input name="menu_title" class="form-control" value="{{ $page->menu?->title }}">
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Menu Icon</label>
                                <input name="menu_icon" class="form-control" list="menu-icon-options-{{ $page->id }}" value="{{ $page->menu?->icon }}" placeholder="bi-stars">
                                <datalist id="menu-icon-options-{{ $page->id }}">
                                    @foreach($menuIconOptions as $iconClass => $label)
                                        <option value="{{ $iconClass }}">{{ $label }}</option>
                                    @endforeach
                                </datalist>
                            </div>
                            <div class="col-lg-4 d-flex align-items-end gap-3 flex-wrap">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_published" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_published" value="1" id="published-{{ $page->id }}" @checked($page->is_published)>
                                    <label class="form-check-label" for="published-{{ $page->id }}">Published</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="show_on_home" value="0">
                                    <input class="form-check-input" type="checkbox" name="show_on_home" value="1" id="home-{{ $page->id }}" @checked($page->show_on_home)>
                                    <label class="form-check-label" for="home-{{ $page->id }}">Show On Home</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="menu_is_active" value="0">
                                    <input class="form-check-input" type="checkbox" name="menu_is_active" value="1" id="menu-{{ $page->id }}" @checked($page->menu?->is_active)>
                                    <label class="form-check-label" for="menu-{{ $page->id }}">Menu Active</label>
                                </div>
                            </div>
                            <div class="col-12 d-flex gap-2 flex-wrap">
                                <button class="btn btn-gold">Save Changes</button>
                                <a href="{{ route('pages.show', $page->slug) }}" class="btn btn-outline-light" target="_blank" rel="noopener">Preview Page</a>
                            </div>
                        </form>
                        <form method="POST" action="{{ route('admin.dynamic-pages.destroy', $page) }}" class="mt-3">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete Page & Linked Menu</button>
                        </form>
                    </div>
                </div>
            </article>
        @empty
            <p class="text-light-emphasis mb-0">No dynamic pages created yet.</p>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $pages->links() }}
    </div>
</div>
@endsection