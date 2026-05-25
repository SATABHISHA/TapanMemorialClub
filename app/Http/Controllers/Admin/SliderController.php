<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SliderController extends Controller
{
    public function index(): View
    {
        $sliders = Slider::query()->latest()->paginate(20);

        return view('admin.sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.sliders.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Slider::query()->create($request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'cta_text' => ['nullable', 'string', 'max:120'],
            'cta_link' => ['nullable', 'url'],
            'media_library_id' => ['nullable', 'integer'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'ken_burns' => ['nullable', 'boolean'],
        ]));

        return back()->with('status', 'Slider created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.sliders.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('admin.sliders.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slider $slider): RedirectResponse
    {
        $slider->update($request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'cta_text' => ['nullable', 'string', 'max:120'],
            'cta_link' => ['nullable', 'url'],
            'media_library_id' => ['nullable', 'integer'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'ken_burns' => ['nullable', 'boolean'],
        ]));

        return back()->with('status', 'Slider updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider): RedirectResponse
    {
        $slider->delete();

        return back()->with('status', 'Slider deleted.');
    }
}
