<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Submenu;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubmenuController extends Controller
{
    public function index(): View
    {
        $submenus = Submenu::query()->with('menu:id,title')->latest()->paginate(25);
        $menus = Menu::query()->orderBy('title')->get(['id', 'title']);

        return view('admin.submenus.index', compact('submenus', 'menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.submenus.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Submenu::query()->create($request->validate([
            'menu_id' => ['required', 'integer', 'exists:menus,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:120'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'open_in_new_tab' => ['nullable', 'boolean'],
        ]));

        return back()->with('status', 'Submenu created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.submenus.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('admin.submenus.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Submenu $submenu): RedirectResponse
    {
        $submenu->update($request->validate([
            'menu_id' => ['required', 'integer', 'exists:menus,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:120'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'open_in_new_tab' => ['nullable', 'boolean'],
        ]));

        return back()->with('status', 'Submenu updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Submenu $submenu): RedirectResponse
    {
        $submenu->delete();

        return back()->with('status', 'Submenu deleted.');
    }
}
