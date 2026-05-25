<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDynamicPageRequest;
use App\Http\Requests\UpdateDynamicPageRequest;
use App\Models\DynamicPage;
use App\Models\Menu;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DynamicPageController extends Controller
{
    public function __construct(private readonly MediaService $mediaService)
    {
    }

    public function index(): View
    {
        $pages = DynamicPage::query()->with(['menu', 'media'])->latest()->paginate(15);

        return view('admin.dynamic-pages.index', compact('pages'));
    }

    public function store(StoreDynamicPageRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $page = new DynamicPage();
        $slug = $this->resolveUniqueSlug($data['title']);
        $page->fill([
            'title' => $data['title'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'content' => $data['content'],
            'is_published' => $request->boolean('is_published', true),
            'show_on_home' => $request->boolean('show_on_home', true),
            'sort_order' => $data['sort_order'] ?? 0,
            'created_by' => $request->user()?->id,
        ]);

        if ($request->hasFile('image')) {
            $page->media_library_id = $this->mediaService->uploadAsBlob(
                $request->file('image'),
                $request->user()?->id,
                'dynamic-pages'
            )->id;
        }

        $page->save();

        $menu = $this->syncMenu($page, $data, $request->boolean('menu_is_active', true));
        $page->menu_id = $menu->id;
        $page->save();

        return back()->with('status', 'Dynamic page created successfully.');
    }

    public function update(UpdateDynamicPageRequest $request, DynamicPage $dynamicPage): RedirectResponse
    {
        $data = $request->validated();
        $slug = $this->resolveUniqueSlug($data['title'], $dynamicPage->id);

        $dynamicPage->fill([
            'title' => $data['title'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'content' => $data['content'],
            'is_published' => $request->boolean('is_published', false),
            'show_on_home' => $request->boolean('show_on_home', false),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        if ($request->hasFile('image')) {
            if ($dynamicPage->media_library_id && ($oldMedia = $this->mediaService->find($dynamicPage->media_library_id))) {
                $this->mediaService->delete($oldMedia);
            }

            $dynamicPage->media_library_id = $this->mediaService->uploadAsBlob(
                $request->file('image'),
                $request->user()?->id,
                'dynamic-pages'
            )->id;
        }

        $dynamicPage->save();

        $menu = $this->syncMenu($dynamicPage, $data, $request->boolean('menu_is_active', false));
        $dynamicPage->menu_id = $menu->id;
        $dynamicPage->save();

        return back()->with('status', 'Dynamic page updated successfully.');
    }

    public function destroy(DynamicPage $dynamicPage): RedirectResponse
    {
        if ($dynamicPage->menu) {
            $dynamicPage->menu->delete();
        }

        if ($dynamicPage->media_library_id && ($media = $this->mediaService->find($dynamicPage->media_library_id))) {
            $this->mediaService->delete($media);
        }

        $dynamicPage->delete();

        return back()->with('status', 'Dynamic page removed.');
    }

    private function syncMenu(DynamicPage $page, array $data, bool $isActive): Menu
    {
        $menu = $page->menu ?: new Menu();
        $menu->fill([
            'title' => $data['menu_title'] ?? $page->title,
            'slug' => Str::slug($data['menu_title'] ?? $page->title),
            'type' => 'internal',
            'url' => '/pages/'.$page->slug,
            'icon' => $data['menu_icon'] ?? null,
            'sort_order' => $data['menu_sort_order'] ?? $page->sort_order,
            'is_active' => $isActive,
            'open_in_new_tab' => false,
            'created_by' => $page->created_by,
        ]);
        $menu->save();

        return $menu;
    }

    private function resolveUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title) ?: 'page';
        $slug = $baseSlug;
        $counter = 2;

        while (DynamicPage::query()
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}