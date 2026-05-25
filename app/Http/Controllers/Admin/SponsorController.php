<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SponsorController extends Controller
{
    public function index(): View
    {
        $sponsors = Sponsor::query()->latest()->paginate(20);

        return view('admin.sponsors.index', compact('sponsors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.sponsors.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Sponsor::query()->create($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'website_url' => ['nullable', 'url'],
            'tier' => ['nullable', 'string', 'max:120'],
            'logo_media_id' => ['nullable', 'integer'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]));

        return back()->with('status', 'Sponsor created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.sponsors.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('admin.sponsors.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sponsor $sponsor): RedirectResponse
    {
        $sponsor->update($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'website_url' => ['nullable', 'url'],
            'tier' => ['nullable', 'string', 'max:120'],
            'logo_media_id' => ['nullable', 'integer'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]));

        return back()->with('status', 'Sponsor updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sponsor $sponsor): RedirectResponse
    {
        $sponsor->delete();

        return back()->with('status', 'Sponsor deleted.');
    }
}
