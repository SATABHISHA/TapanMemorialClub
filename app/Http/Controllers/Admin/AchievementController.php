<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AchievementController extends Controller
{
    public function index(): View
    {
        $achievements = Achievement::query()->latest()->paginate(20);

        return view('admin.achievements.index', compact('achievements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.achievements.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'performance_id' => ['nullable', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'achievement_date' => ['nullable', 'date'],
            'year' => ['nullable', 'integer'],
            'badge_color' => ['nullable', 'string', 'max:20'],
            'icon' => ['nullable', 'string', 'max:120'],
            'media_library_id' => ['nullable', 'integer', 'exists:media_libraries,id'],
            'sort_order' => ['nullable', 'integer'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['is_featured'] = $request->boolean('is_featured');

        Achievement::query()->create($validated);

        return back()->with('status', 'Achievement created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.achievements.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('admin.achievements.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Achievement $achievement): RedirectResponse
    {
        $validated = $request->validate([
            'performance_id' => ['nullable', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'achievement_date' => ['nullable', 'date'],
            'year' => ['nullable', 'integer'],
            'badge_color' => ['nullable', 'string', 'max:20'],
            'icon' => ['nullable', 'string', 'max:120'],
            'media_library_id' => ['nullable', 'integer', 'exists:media_libraries,id'],
            'sort_order' => ['nullable', 'integer'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $validated['sort_order'] = (int) ($validated['sort_order'] ?? ($achievement->sort_order ?? 0));
        $validated['is_featured'] = $request->boolean('is_featured');

        $achievement->update($validated);

        return back()->with('status', 'Achievement updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Achievement $achievement): RedirectResponse
    {
        $achievement->delete();

        return back()->with('status', 'Achievement deleted.');
    }
}
