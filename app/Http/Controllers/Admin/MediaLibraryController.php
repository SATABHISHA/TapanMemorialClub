<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMediaLibraryRequest;
use App\Models\MediaLibrary;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MediaLibraryController extends Controller
{
    public function __construct(private readonly MediaService $mediaService)
    {
    }

    public function index(): View
    {
        $mediaItems = $this->mediaService->paginated();

        return view('admin.media.index', compact('mediaItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.media-library.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMediaLibraryRequest $request): RedirectResponse
    {
        $this->mediaService->upload(
            $request->file('image'),
            $request->user()?->id,
            $request->input('module')
        );

        return back()->with('status', 'Image uploaded and optimized.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.media-library.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('admin.media-library.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreMediaLibraryRequest $request, MediaLibrary $mediaLibrary): RedirectResponse
    {
        if ($request->hasFile('image')) {
            $this->mediaService->delete($mediaLibrary);
            $this->mediaService->upload($request->file('image'), $request->user()?->id, $request->input('module'));
        }

        return back()->with('status', 'Media updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MediaLibrary $mediaLibrary): RedirectResponse
    {
        $this->mediaService->delete($mediaLibrary);

        return back()->with('status', 'Media deleted.');
    }
}
