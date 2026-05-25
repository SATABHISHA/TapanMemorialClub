<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function __construct(private readonly MediaService $mediaService)
    {
    }

    public function index(): View
    {
        $galleryItems = GalleryImage::with('media')->latest()->paginate(30);

        return view('admin.gallery.index', compact('galleryItems'));
    }

    public function create()
    {
        return redirect()->route('admin.gallery.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'media_library_id' => ['nullable', 'integer', 'exists:media_libraries,id'],
            'image'            => ['nullable', 'image', 'max:10240'],
            'title'            => ['nullable', 'string', 'max:255'],
            'category'         => ['nullable', 'string', 'max:120'],
            'is_featured'      => ['nullable', 'boolean'],
            'display_order'    => ['nullable', 'integer'],
        ]);

        // DB column is non-nullable; keep a stable integer default.
        $validated['display_order'] = (int) ($validated['display_order'] ?? 0);
        $validated['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('image')) {
            $media = $this->mediaService->upload(
                $request->file('image'),
                $request->user()?->id,
                'gallery'
            );
            $validated['media_library_id'] = $media->id;
        }

        unset($validated['image']);
        GalleryImage::query()->create($validated);

        return back()->with('status', 'Gallery item created.');
    }

    public function show(GalleryImage $gallery): JsonResponse
    {
        $gallery->load('media');

        return response()->json([
            'id'              => $gallery->id,
            'title'           => $gallery->title,
            'category'        => $gallery->category,
            'media_library_id'=> $gallery->media_library_id,
            'is_featured'     => $gallery->is_featured,
            'display_order'   => $gallery->display_order,
            'media_thumb_url' => $gallery->media_library_id
                ? route('media.thumb', $gallery->media_library_id)
                : null,
            'media_full_url'  => $gallery->media_library_id
                ? route('media.show', $gallery->media_library_id)
                : null,
        ]);
    }

    public function edit(string $id)
    {
        return redirect()->route('admin.gallery.index');
    }

    public function update(Request $request, GalleryImage $gallery): RedirectResponse
    {
        $validated = $request->validate([
            'media_library_id' => ['nullable', 'integer', 'exists:media_libraries,id'],
            'image'            => ['nullable', 'image', 'max:10240'],
            'title'            => ['nullable', 'string', 'max:255'],
            'category'         => ['nullable', 'string', 'max:120'],
            'is_featured'      => ['nullable', 'boolean'],
            'display_order'    => ['nullable', 'integer'],
        ]);

        // Avoid null writes for non-nullable display_order.
        $validated['display_order'] = (int) ($validated['display_order'] ?? ($gallery->display_order ?? 0));
        $validated['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('image')) {
            $media = $this->mediaService->upload(
                $request->file('image'),
                $request->user()?->id,
                'gallery'
            );
            $validated['media_library_id'] = $media->id;
        }

        unset($validated['image']);
        $gallery->update($validated);

        return back()->with('status', 'Gallery item updated.');
    }

    public function destroy(GalleryImage $gallery): RedirectResponse
    {
        $gallery->delete();

        return back()->with('status', 'Gallery item deleted.');
    }
}
