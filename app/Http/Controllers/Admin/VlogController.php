<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Submenu;
use App\Models\Vlog;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VlogController extends Controller
{
    public function __construct(private readonly MediaService $mediaService)
    {
    }

    public function index(): View
    {
        $vlogs = Vlog::query()
            ->with(['image', 'menu', 'submenu'])
            ->latest('id')
            ->paginate(20);

        $menus    = Menu::query()->orderBy('sort_order')->orderBy('title')->get();
        $submenus = Submenu::query()->with('menu')->orderBy('sort_order')->orderBy('title')->get();

        return view('admin.vlogs.index', compact('vlogs', 'menus', 'submenus'));
    }

    public function create()
    {
        return redirect()->route('admin.vlogs.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['created_by'] = $request->user()?->id;

        if ($request->hasFile('image')) {
            $media = $this->mediaService->upload($request->file('image'), $request->user()?->id, 'vlogs');
            $data['image_media_id'] = $media->id;
        }
        unset($data['image']);

        Vlog::create($data);

        return back()->with('status', 'Vlog created.');
    }

    public function show(string $id)
    {
        return redirect()->route('admin.vlogs.index');
    }

    public function edit(string $id)
    {
        return redirect()->route('admin.vlogs.index');
    }

    public function update(Request $request, Vlog $vlog): RedirectResponse
    {
        $data = $this->validateData($request, $vlog->id);

        if ($request->hasFile('image')) {
            $media = $this->mediaService->upload($request->file('image'), $request->user()?->id, 'vlogs');
            $data['image_media_id'] = $media->id;
        }
        unset($data['image']);

        $vlog->update($data);

        return back()->with('status', 'Vlog updated.');
    }

    public function destroy(Vlog $vlog): RedirectResponse
    {
        $vlog->delete();

        return back()->with('status', 'Vlog deleted.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'slug'           => ['nullable', 'string', 'max:255'],
            'description'    => ['nullable', 'string', 'max:1000'],
            'body'           => ['nullable', 'string'],
            'video_url'      => ['nullable', 'url', 'max:500'],
            'image'          => ['nullable', 'file', 'image', 'max:10240'],
            'menu_id'        => ['nullable', 'integer', 'exists:menus,id'],
            'submenu_id'     => ['nullable', 'integer', 'exists:submenus,id'],
            'status'         => ['required', 'in:draft,published'],
            'published_at'   => ['nullable', 'date'],
            'sort_order'     => ['nullable', 'integer'],
            'is_featured'    => ['nullable', 'boolean'],
        ]);
    }
}
