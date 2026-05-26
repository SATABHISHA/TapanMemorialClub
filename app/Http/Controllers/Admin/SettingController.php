<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    private const GROUP_PREFIX_MAP = [
        'mail_'       => 'mail',
        'contact_'    => 'contact',
        'social_'     => 'social',
        'developer_'  => 'branding',
        'club_logo_'  => 'branding',   // logo-related keys stay in branding
        'club_'       => 'club',        // identity/content keys go to club
    ];

    public function index(): View
    {
        $settings = Setting::query()->latest()->paginate(30);

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.settings.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Setting::query()->create($request->validate([
            'group' => ['required', 'string', 'max:120'],
            'key' => ['required', 'string', 'max:120', 'unique:settings,key'],
            'value' => ['nullable', 'string'],
            'type' => ['nullable', 'string', 'max:50'],
            'is_public' => ['nullable', 'boolean'],
        ]));

        return back()->with('status', 'Setting added.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.settings.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('admin.settings.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting): RedirectResponse
    {
        $setting->update($request->validate([
            'group' => ['required', 'string', 'max:120'],
            'key' => ['required', 'string', 'max:120', 'unique:settings,key,'.$setting->id],
            'value' => ['nullable', 'string'],
            'type' => ['nullable', 'string', 'max:50'],
            'is_public' => ['nullable', 'boolean'],
        ]));

        return back()->with('status', 'Setting updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting): RedirectResponse
    {
        $setting->delete();

        return back()->with('status', 'Setting deleted.');
    }

    /**
     * Bulk update mail / config settings from grouped form.
     */
    public function bulkUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'settings.contact_latitude'  => ['nullable', 'numeric', 'between:-90,90'],
            'settings.contact_longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'club_logo_file'             => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:2048'],
            'developer_logo_file'        => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:2048'],
        ]);

        $payload = $request->input('settings', []);

        // Merge in boolean sentinel keys so unchecked checkboxes persist as '0'
        $booleanKeys = $request->input('boolean_keys', []);
        foreach ($booleanKeys as $bk) {
            if (! array_key_exists($bk, $payload)) {
                $payload[$bk] = '0';
            }
        }

        // Handle logo image file uploads
        $logoUploads = [
            'club_logo_file'      => ['key' => 'club_logo_url',      'name' => 'club-logo'],
            'developer_logo_file' => ['key' => 'developer_logo_url', 'name' => 'ahanova-logo'],
        ];

        foreach ($logoUploads as $inputName => $config) {
            if ($request->hasFile($inputName) && $request->file($inputName)->isValid()) {
                $file     = $request->file($inputName);
                $ext      = strtolower($file->getClientOriginalExtension());
                $filename = $config['name'] . '.' . $ext;
                $file->move(public_path('assets/images'), $filename);
                // Append a version timestamp so browsers always fetch the new file
                // instead of serving a cached copy of the previous image.
                $payload[$config['key']] = asset('assets/images/' . $filename) . '?v=' . time();
            }
        }

        foreach ($payload as $key => $value) {
            $value = is_null($value) ? '' : trim((string) $value);

            Setting::query()->updateOrCreate(
                ['key' => $key],
                [
                    'group'     => $this->resolveGroup($key),
                    'value'     => $value,
                    'type'      => $this->resolveType($key),
                    'is_public' => ! Str::startsWith($key, 'mail_'),
                ]
            );
        }

        return back()->with('status', 'Settings saved.');
    }

    public function destroyByKey(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:120'],
        ]);

        Setting::query()->where('key', $validated['key'])->delete();

        return back()->with('status', 'Setting deleted.');
    }

    private function resolveGroup(string $key): string
    {
        foreach (self::GROUP_PREFIX_MAP as $prefix => $group) {
            if (Str::startsWith($key, $prefix)) {
                return $group;
            }
        }

        return 'general';
    }

    private function resolveType(string $key): string
    {
        return match (true) {
            str_contains($key, 'password') => 'password',
            str_contains($key, 'email') => 'email',
            str_contains($key, 'latitude') || str_contains($key, 'longitude') => 'number',
            str_contains($key, '_url') || str_contains($key, '_map') => 'url',
            str_contains($key, 'address') || str_ends_with($key, '_text') => 'textarea',
            str_contains($key, '_visible') => 'boolean',
            default => 'text',
        };
    }
}
