@extends('layouts.admin')

@php
    $all = \App\Models\Setting::query()->get()->keyBy('key');
    $grouped = $all->values()->groupBy('group');
    $mail = $grouped->get('mail', collect())->keyBy('key');
    $valueOf = fn (string $key, string $default = ''): string => (string) (optional($all->get($key))->value ?? $default);

    $developerLogoVisible = $valueOf('developer_logo_visible', '1') !== '0';
    $clubLogoVisible = $valueOf('club_logo_visible', '1') !== '0';

    $contactFields = [
        'contact_address' => ['label' => 'Address', 'type' => 'textarea', 'placeholder' => 'Kolkata, West Bengal, India'],
        'contact_phone' => ['label' => 'Phone', 'type' => 'text', 'placeholder' => '+91-9000000000'],
        'contact_email' => ['label' => 'Email', 'type' => 'email', 'placeholder' => 'contact@tapanmemorialclub.com'],
        'contact_latitude' => ['label' => 'Latitude', 'type' => 'number', 'placeholder' => '22.5726', 'step' => '0.000001', 'min' => '-90', 'max' => '90'],
        'contact_longitude' => ['label' => 'Longitude', 'type' => 'number', 'placeholder' => '88.3639', 'step' => '0.000001', 'min' => '-180', 'max' => '180'],
        'contact_map_embed_url' => ['label' => 'Google Map Embed URL', 'type' => 'url', 'placeholder' => 'https://www.google.com/maps?q=Kolkata&output=embed'],
        'contact_whatsapp_number' => ['label' => 'WhatsApp Number (Digits Only)', 'type' => 'text', 'placeholder' => '919999999999'],
    ];

    $socialFields = [
        'social_instagram_url' => ['label' => 'Instagram URL', 'icon' => 'bi-instagram'],
        'social_facebook_url' => ['label' => 'Facebook URL', 'icon' => 'bi-facebook'],
        'social_youtube_url' => ['label' => 'YouTube URL', 'icon' => 'bi-youtube'],
        'social_twitter_url' => ['label' => 'X / Twitter URL', 'icon' => 'bi-twitter-x'],
        'social_linkedin_url' => ['label' => 'LinkedIn URL', 'icon' => 'bi-linkedin'],
    ];

    $previewAddress = $valueOf('contact_address', 'Kolkata, West Bengal, India');
    $previewPhone = $valueOf('contact_phone', '+91-9000000000');
    $previewEmail = $valueOf('contact_email', 'contact@tapanmemorialclub.com');
    $previewLat = trim($valueOf('contact_latitude'));
    $previewLng = trim($valueOf('contact_longitude'));
    $previewMap = $valueOf('contact_map_embed_url', 'https://www.google.com/maps?q=Kolkata&output=embed');
    $hasPreviewCoords = is_numeric($previewLat) && is_numeric($previewLng);
    if ($hasPreviewCoords) {
        $previewMap = 'https://www.google.com/maps?q='.$previewLat.','.$previewLng.'&z=15&output=embed';
    }

    $mailFields = [
        'mail_mailer' => ['label' => 'Mailer', 'type' => 'select', 'options' => ['smtp' => 'SMTP', 'log' => 'Log (dev)', 'sendmail' => 'Sendmail']],
        'mail_host' => ['label' => 'SMTP Host', 'type' => 'text', 'placeholder' => 'smtp.example.com'],
        'mail_port' => ['label' => 'SMTP Port', 'type' => 'text', 'placeholder' => '587'],
        'mail_username' => ['label' => 'Username', 'type' => 'text'],
        'mail_password' => ['label' => 'Password', 'type' => 'password'],
        'mail_encryption' => ['label' => 'Encryption', 'type' => 'select', 'options' => ['tls' => 'TLS', 'ssl' => 'SSL', '' => 'None']],
        'mail_from_address' => ['label' => 'From Address', 'type' => 'email'],
        'mail_from_name' => ['label' => 'From Name', 'type' => 'text'],
    ];
@endphp

@section('content')
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="glass-card p-4 h-100">
            <h4 class="mb-1 text-warning">Contact Desk & Location</h4>
            <p class="text-light-emphasis small mb-3">Manage contact details, map location, and WhatsApp redirect from admin.</p>

            <form method="POST" action="{{ route('admin.settings.bulk-update') }}" class="row g-3">
                @csrf
                @foreach($contactFields as $key => $cfg)
                    @php $value = $valueOf($key); @endphp
                    <div class="col-12">
                        <label class="form-label small text-uppercase text-warning">{{ $cfg['label'] }}</label>
                        @if($cfg['type'] === 'textarea')
                            <textarea name="settings[{{ $key }}]" rows="2" class="form-control" placeholder="{{ $cfg['placeholder'] }}">{{ $value }}</textarea>
                        @else
                            <input
                                type="{{ $cfg['type'] }}"
                                name="settings[{{ $key }}]"
                                class="form-control"
                                value="{{ $value }}"
                                placeholder="{{ $cfg['placeholder'] }}"
                                @if(isset($cfg['step'])) step="{{ $cfg['step'] }}" @endif
                                @if(isset($cfg['min'])) min="{{ $cfg['min'] }}" @endif
                                @if(isset($cfg['max'])) max="{{ $cfg['max'] }}" @endif
                            >
                        @endif
                    </div>
                @endforeach
                <div class="col-12 d-flex flex-wrap gap-2 align-items-center">
                    <button type="button" class="btn btn-outline-info btn-sm" id="detect-current-location">
                        <i class="bi bi-crosshair"></i> Detect Current Lat/Lng
                    </button>
                    <button type="button" class="btn btn-outline-warning btn-sm" id="apply-latlng-map">
                        <i class="bi bi-geo-alt"></i> Apply Lat/Lng To Map
                    </button>
                    <small id="location-detect-status" class="text-light-emphasis"></small>
                </div>
                <div class="col-12">
                    <button class="btn btn-gold">Save Contact Settings</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="glass-card p-4 h-100">
            <h5 class="mb-3">Contact Preview</h5>
            <iframe class="w-100 rounded border-0 mb-3" style="height: 170px;" src="{{ $previewMap }}" loading="lazy" id="contact-map-preview"></iframe>
            <div class="small text-light-emphasis">
                <p class="mb-2"><strong class="text-warning">Address:</strong> {{ $previewAddress }}</p>
                <p class="mb-2"><strong class="text-warning">Phone:</strong> {{ $previewPhone }}</p>
                <p class="mb-0"><strong class="text-warning">Email:</strong> {{ $previewEmail }}</p>
            </div>
        </div>
    </div>
</div>

<div class="glass-card p-4 mb-4">
    <h4 class="mb-1 text-warning">Media / Social Redirect Links</h4>
    <p class="text-light-emphasis small mb-3">Set social URLs shown in top bar and footer. Empty value means hidden from frontend.</p>

    <form method="POST" action="{{ route('admin.settings.bulk-update') }}" class="row g-3">
        @csrf
        @foreach($socialFields as $key => $cfg)
            @php $value = $valueOf($key); @endphp
            <div class="col-lg-6">
                <label class="form-label small text-uppercase text-warning"><i class="bi {{ $cfg['icon'] }} me-1"></i>{{ $cfg['label'] }}</label>
                <input type="url" name="settings[{{ $key }}]" class="form-control" value="{{ $value }}" placeholder="https://...">
            </div>
        @endforeach
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <button class="btn btn-gold">Save Media Links</button>
            <div class="d-flex gap-2">
                @foreach($socialFields as $key => $cfg)
                    @php $url = $valueOf($key); @endphp
                    @if($url !== '')
                        <a href="{{ $url }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-light"><i class="bi {{ $cfg['icon'] }} me-1"></i>Preview</a>
                    @endif
                @endforeach
            </div>
        </div>
    </form>
</div>

<div class="glass-card p-4 mb-4">
    <h5 class="mb-3">Delete Contact / Social Keys</h5>
    <div class="d-flex flex-wrap gap-2">
        @foreach(array_merge(array_keys($contactFields), array_keys($socialFields)) as $key)
            <form method="POST" action="{{ route('admin.settings.delete-key') }}">
                @csrf
                <input type="hidden" name="key" value="{{ $key }}">
                <button type="submit" class="btn btn-sm btn-outline-danger">Delete {{ $key }}</button>
            </form>
        @endforeach
    </div>
</div>

<div class="glass-card p-4 mb-4">
    <h4 class="mb-1 text-warning">SMTP / Email Configuration</h4>
    <p class="text-light-emphasis small mb-3">Add your SMTP credentials here. Once saved, the app will use them for password-reset emails and notifications. Leave blank to fall back to the default <code>log</code> driver.</p>
    @if(session('status'))
        <div class="alert alert-success py-2">{{ session('status') }}</div>
    @endif
    <form method="POST" action="{{ route('admin.settings.bulk-update') }}" class="row g-3">
        @csrf
        @foreach($mailFields as $key => $cfg)
            @php $value = optional($mail->get($key))->value; @endphp
            <div class="col-md-6 col-lg-4">
                <label class="form-label small text-uppercase text-warning">{{ $cfg['label'] }}</label>
                @if($cfg['type'] === 'select')
                    <select name="settings[{{ $key }}]" class="form-select">
                        @foreach($cfg['options'] as $optV => $optL)
                            <option value="{{ $optV }}" @selected($value === $optV)>{{ $optL }}</option>
                        @endforeach
                    </select>
                @else
                    <input type="{{ $cfg['type'] }}" name="settings[{{ $key }}]" class="form-control" value="{{ $value }}" placeholder="{{ $cfg['placeholder'] ?? '' }}">
                @endif
            </div>
        @endforeach
        <div class="col-12">
            <button class="btn btn-gold">Save Mail Settings</button>
        </div>
    </form>
</div>

{{-- ===================== BRANDING / LOGO SETTINGS ===================== --}}
<div class="glass-card p-4 mb-4">
    <h4 class="mb-1 text-warning"><i class="bi bi-image me-2"></i>Branding &amp; Logo Settings</h4>
    <p class="text-light-emphasis small mb-4">Control logos and developer credit displayed on the website. Toggle visibility or change the image URL at any time.</p>

    <div class="row g-4">
        {{-- ---- TMC Club Logo ---- --}}
        <div class="col-lg-6">
            <div class="border border-secondary rounded-3 p-3 h-100">
                <h6 class="text-info mb-3"><i class="bi bi-shield-fill me-1"></i>Club Logo (Tapan Memorial Club)</h6>
                <form method="POST" action="{{ route('admin.settings.bulk-update') }}"
                      enctype="multipart/form-data" class="row g-3" id="form-club-logo">
                    @csrf
                    <input type="hidden" name="boolean_keys[]" value="club_logo_visible">

                    {{-- File Upload --}}
                    <div class="col-12">
                        <label class="form-label small text-uppercase text-warning">Upload Image</label>
                        <label for="club-logo-file" class="tmc-upload-drop w-100" id="club-logo-drop">
                            <i class="bi bi-cloud-upload fs-3 text-info"></i>
                            <span class="d-block mt-1 fw-semibold">Click or drag &amp; drop to upload</span>
                            <span class="d-block text-light-emphasis small mt-1" id="club-logo-file-name">PNG, JPG, WEBP &mdash; max 2 MB</span>
                        </label>
                        <input type="file" id="club-logo-file" name="club_logo_file"
                               accept="image/png,image/jpeg,image/webp,image/gif"
                               class="d-none" data-preview="club-logo-preview" data-name="club-logo-file-name">
                        {{-- Size guidance --}}
                        <div class="mt-2 p-2 rounded-2" style="background:rgba(255,255,255,.04);border:1px dashed rgba(255,255,255,.15);">
                            <p class="mb-1 small text-info"><i class="bi bi-rulers me-1"></i><strong>Recommended size: 500 &times; 500 px (square)</strong></p>
                            <p class="mb-0 text-light-emphasis" style="font-size:.78rem;">
                                The club logo is displayed as a <strong>circle</strong> (cropped to center) in three places:
                            </p>
                            <ul class="mb-0 text-light-emphasis ps-3" style="font-size:.78rem;">
                                <li>Navbar: 68 &times; 68 px circle</li>
                                <li>Footer: 48 &times; 48 px circle</li>
                                <li>Loading screen: 80 &times; 80 px circle</li>
                            </ul>
                            <p class="mb-0 mt-1 text-light-emphasis" style="font-size:.78rem;">Use a square image with the logo centered. PNG with a transparent or solid-color background works best.</p>
                        </div>
                    </div>

                    {{-- Preview --}}
                    <div class="col-12">
                        <label class="form-label small text-uppercase text-warning">Preview</label>
                        <div class="d-flex align-items-center gap-3">
                            <img id="club-logo-preview"
                                 src="{{ $valueOf('club_logo_url') ?: asset('assets/images/logo.jpeg') }}"
                                 alt="Club Logo Preview"
                                 style="height:72px;width:auto;max-width:180px;object-fit:contain;border-radius:6px;background:rgba(255,255,255,.05);padding:4px;">
                        </div>
                    </div>

                    {{-- URL fallback --}}
                    <div class="col-12">
                        <label class="form-label small text-uppercase text-warning">Or paste image URL</label>
                        <input type="url" name="settings[club_logo_url]" class="form-control" id="club-logo-url-input"
                               value="{{ $valueOf('club_logo_url') }}"
                               placeholder="https://... (leave empty to use uploaded file)">
                        <div class="form-text text-light-emphasis">Uploading a file above will override this URL.</div>
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="settings[club_logo_visible]" value="1"
                                   id="club-logo-visible" @checked($clubLogoVisible)>
                            <label class="form-check-label text-light-emphasis" for="club-logo-visible">
                                Show club logo on website
                            </label>
                        </div>
                    </div>

                    {{-- Size control --}}
                    <div class="col-12">
                        <label class="form-label small text-uppercase text-warning">Display Size</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="range" class="form-range" style="flex:1"
                                   name="settings[club_logo_size]"
                                   min="40" max="160" step="4"
                                   value="{{ $valueOf('club_logo_size', '68') }}"
                                   id="club-logo-size-range">
                            <span class="badge bg-info text-dark fw-bold" id="club-logo-size-badge"
                                  style="min-width:52px;font-size:.85rem;">{{ $valueOf('club_logo_size', '68') }} px</span>
                        </div>
                        <div class="form-text text-light-emphasis" id="club-logo-size-hint"></div>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-gold btn-sm">Save Club Logo</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ---- Developer / Company Logo ---- --}}
        <div class="col-lg-6">
            <div class="border border-secondary rounded-3 p-3 h-100">
                <h6 class="text-info mb-3"><i class="bi bi-building me-1"></i>Developer / Company Logo (Footer Credit)</h6>
                <form method="POST" action="{{ route('admin.settings.bulk-update') }}"
                      enctype="multipart/form-data" class="row g-3" id="form-dev-logo">
                    @csrf
                    <input type="hidden" name="boolean_keys[]" value="developer_logo_visible">

                    <div class="col-12">
                        <label class="form-label small text-uppercase text-warning">Company Name</label>
                        <input type="text" name="settings[developer_brand_name]" class="form-control"
                               value="{{ $valueOf('developer_brand_name', 'AhaNova AI Technologies Pvt. Ltd.') }}"
                               placeholder="AhaNova AI Technologies Pvt. Ltd.">
                    </div>
                    <div class="col-12">
                        <label class="form-label small text-uppercase text-warning">Company Website URL</label>
                        <input type="url" name="settings[developer_website_url]" class="form-control"
                               value="{{ $valueOf('developer_website_url') }}"
                               placeholder="https://ahanova.in">
                    </div>

                    {{-- File Upload --}}
                    <div class="col-12">
                        <label class="form-label small text-uppercase text-warning">Upload Logo Image</label>
                        <label for="dev-logo-file" class="tmc-upload-drop w-100" id="dev-logo-drop">
                            <i class="bi bi-cloud-upload fs-3 text-info"></i>
                            <span class="d-block mt-1 fw-semibold">Click or drag &amp; drop to upload</span>
                            <span class="d-block text-light-emphasis small mt-1" id="dev-logo-file-name">PNG, JPG, WEBP &mdash; max 2 MB</span>
                        </label>
                        <input type="file" id="dev-logo-file" name="developer_logo_file"
                               accept="image/png,image/jpeg,image/webp,image/gif"
                               class="d-none" data-preview="dev-logo-preview" data-name="dev-logo-file-name">
                        {{-- Size guidance --}}
                        <div class="mt-2 p-2 rounded-2" style="background:rgba(255,255,255,.04);border:1px dashed rgba(255,255,255,.15);">
                            <p class="mb-1 small text-info"><i class="bi bi-rulers me-1"></i><strong>Recommended size: 400 &times; 120 px (landscape)</strong></p>
                            <p class="mb-0 text-light-emphasis" style="font-size:.78rem;">
                                The company logo is displayed in the <strong>footer credit bar</strong>:
                            </p>
                            <ul class="mb-0 text-light-emphasis ps-3" style="font-size:.78rem;">
                                <li>Desktop: up to 220 px wide, ~50 px tall</li>
                                <li>Mobile: up to 64 vw wide, ~38 px tall</li>
                            </ul>
                            <p class="mb-0 mt-1 text-light-emphasis" style="font-size:.78rem;">Use a <strong>horizontal (wide) logo</strong> with a transparent background. PNG or WEBP format recommended.</p>
                        </div>
                    </div>

                    {{-- Preview --}}
                    <div class="col-12">
                        <label class="form-label small text-uppercase text-warning">Preview</label>
                        @php
                            // Strip ?v=... query param to get the bare path for file_exists check
                            $devStoredUrl = $valueOf('developer_logo_url');
                            $devPreviewSrc = $devStoredUrl;
                            if (!$devPreviewSrc) {
                                foreach (['ahanova-logo.png','ahanova-logo.jpg','ahanova-logo.jpeg','ahanova-logo.webp'] as $c) {
                                    if (file_exists(public_path('assets/images/'.$c))) {
                                        // Add cache-buster based on file modification time
                                        $devPreviewSrc = asset('assets/images/'.$c) . '?v=' . filemtime(public_path('assets/images/'.$c));
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <div class="d-flex align-items-center gap-3">
                            <img id="dev-logo-preview"
                                 src="{{ $devPreviewSrc ?: '' }}"
                                 alt="Developer Logo Preview"
                                 style="height:72px;width:auto;max-width:220px;object-fit:contain;border-radius:6px;background:rgba(255,255,255,.05);padding:4px;{{ !$devPreviewSrc ? 'display:none;' : '' }}">
                            @if(!$devPreviewSrc)
                                <span class="text-light-emphasis small">No logo yet &mdash; upload one above.</span>
                            @endif
                        </div>
                    </div>

                    {{-- URL fallback --}}
                    <div class="col-12">
                        <label class="form-label small text-uppercase text-warning">Or paste image URL</label>
                        <input type="url" name="settings[developer_logo_url]" class="form-control" id="dev-logo-url-input"
                               value="{{ $devStoredUrl }}"
                               placeholder="https://... (leave empty to use uploaded file)">
                        <div class="form-text text-light-emphasis">Uploading a file above will override this URL. Leave both empty to auto-detect <code>ahanova-logo.png</code>.</div>
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="settings[developer_logo_visible]" value="1"
                                   id="dev-logo-visible" @checked($developerLogoVisible)>
                            <label class="form-check-label text-light-emphasis" for="dev-logo-visible">
                                Show company logo in footer
                            </label>
                        </div>
                    </div>

                    {{-- Size control --}}
                    <div class="col-12">
                        <label class="form-label small text-uppercase text-warning">Logo Height</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="range" class="form-range" style="flex:1"
                                   name="settings[developer_logo_height]"
                                   min="24" max="200" step="4"
                                   value="{{ $valueOf('developer_logo_height', '60') }}"
                                   id="dev-logo-height-range">
                            <span class="badge bg-info text-dark fw-bold" id="dev-logo-height-badge"
                                  style="min-width:52px;font-size:.85rem;">{{ $valueOf('developer_logo_height', '60') }} px</span>
                        </div>
                        <div class="form-text text-light-emphasis">Width adjusts automatically. Drag to resize the logo in the footer.</div>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-gold btn-sm">Save Company Logo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="glass-card p-4 mb-4">
    <h5 class="mb-3">Add a Custom Setting</h5>
    <form method="POST" action="{{ route('admin.settings.store') }}" class="row g-3">
        @csrf
        <div class="col-md-2"><input name="group" class="form-control" placeholder="Group" required></div>
        <div class="col-md-3"><input name="key" class="form-control" placeholder="Key" required></div>
        <div class="col-md-2"><input name="type" class="form-control" placeholder="Type"></div>
        <div class="col-md-4"><input name="value" class="form-control" placeholder="Value"></div>
        <div class="col-md-1 d-grid"><button class="btn btn-gold">Add</button></div>
    </form>
</div>

@foreach($grouped as $groupName => $items)
    @continue(in_array($groupName, ['mail', 'contact', 'social', 'branding'], true))
    <div class="glass-card p-4 mb-4">
        <h5 class="mb-3 text-info">{{ ucfirst($groupName ?: 'general') }} Settings</h5>
        <table class="table table-dark table-hover align-middle">
            <thead><tr><th>Key</th><th>Value</th><th></th></tr></thead>
            <tbody>
                @foreach($items as $setting)
                    <tr>
                        <td>{{ $setting->key }}</td>
                        <td class="text-truncate" style="max-width: 360px;">{{ \Illuminate\Support\Str::limit($setting->value, 160) }}</td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('admin.settings.destroy', $setting) }}" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endforeach

<script>
    (() => {
        const latInput = document.querySelector('input[name="settings[contact_latitude]"]');
        const lngInput = document.querySelector('input[name="settings[contact_longitude]"]');
        const mapInput = document.querySelector('input[name="settings[contact_map_embed_url]"]');
        const previewFrame = document.getElementById('contact-map-preview');
        const detectBtn = document.getElementById('detect-current-location');
        const applyBtn = document.getElementById('apply-latlng-map');
        const statusEl = document.getElementById('location-detect-status');

        if (!latInput || !lngInput || !mapInput || !previewFrame || !detectBtn || !applyBtn || !statusEl) {
            return;
        }

        const toEmbedUrl = (lat, lng) => `https://www.google.com/maps?q=${encodeURIComponent(`${lat},${lng}`)}&z=15&output=embed`;

        const hasValidCoords = () => {
            const lat = Number.parseFloat(latInput.value);
            const lng = Number.parseFloat(lngInput.value);

            return Number.isFinite(lat) && Number.isFinite(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180;
        };

        const applyCoordsToMap = () => {
            if (!hasValidCoords()) {
                statusEl.textContent = 'Enter valid latitude/longitude values first.';
                return;
            }

            mapInput.value = toEmbedUrl(latInput.value, lngInput.value);
            previewFrame.src = mapInput.value;
            statusEl.textContent = 'Map preview updated from latitude/longitude.';
        };

        mapInput.addEventListener('input', () => {
            if (mapInput.value.trim() !== '') {
                previewFrame.src = mapInput.value;
            }
        });

        applyBtn.addEventListener('click', applyCoordsToMap);

        detectBtn.addEventListener('click', () => {
            if (!navigator.geolocation) {
                statusEl.textContent = 'Geolocation is not supported in this browser.';
                return;
            }

            statusEl.textContent = 'Detecting current location...';

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    latInput.value = position.coords.latitude.toFixed(6);
                    lngInput.value = position.coords.longitude.toFixed(6);
                    applyCoordsToMap();
                    statusEl.textContent = 'Current location detected. Save Contact Settings to publish it.';
                },
                (error) => {
                    statusEl.textContent = `Location detect failed: ${error.message}`;
                },
                { enableHighAccuracy: true, timeout: 12000, maximumAge: 0 }
            );
        });
    })();

    // Logo file upload + URL preview handlers
    (() => {
        // Map: file input id → url input id
        const urlInputMap = {
            'club-logo-file': 'club-logo-url-input',
            'dev-logo-file':  'dev-logo-url-input',
        };

        // File picker → instant preview + clear URL input
        document.querySelectorAll('input[type="file"][data-preview]').forEach(input => {
            input.addEventListener('change', () => {
                const file = input.files[0];
                if (!file) return;
                const preview = document.getElementById(input.dataset.preview);
                const nameEl  = document.getElementById(input.dataset.name);
                if (nameEl) nameEl.textContent = file.name;
                const drop = input.previousElementSibling;
                if (drop) drop.classList.add('tmc-upload-drop--active');
                // Clear the URL fallback input — file upload takes priority
                const urlInput = document.getElementById(urlInputMap[input.id]);
                if (urlInput) urlInput.value = '';
                if (preview) {
                    const reader = new FileReader();
                    reader.onload = e => { preview.src = e.target.result; preview.style.display = ''; };
                    reader.readAsDataURL(file);
                }
            });
            // Drag-and-drop on the label
            const label = input.previousElementSibling;
            if (label) {
                label.addEventListener('dragover', e => { e.preventDefault(); label.classList.add('tmc-upload-drop--hover'); });
                label.addEventListener('dragleave', () => label.classList.remove('tmc-upload-drop--hover'));
                label.addEventListener('drop', e => {
                    e.preventDefault();
                    label.classList.remove('tmc-upload-drop--hover');
                    const dt = e.dataTransfer;
                    if (dt && dt.files.length) {
                        // Use DataTransfer to assign files to the input
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(dt.files[0]);
                        input.files = dataTransfer.files;
                        input.dispatchEvent(new Event('change'));
                    }
                });
            }
        });

        // URL input → live preview (only when no file is selected)
        const urlPairs = [
            ['club-logo-url-input', 'club-logo-preview'],
            ['dev-logo-url-input',  'dev-logo-preview'],
        ];
        urlPairs.forEach(([inputId, previewId]) => {
            const input   = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            if (!input || !preview) return;
            input.addEventListener('input', () => {
                const url = input.value.trim();
                if (url) { preview.src = url; preview.style.display = ''; }
            });
        });
    })();

    // Club logo size slider — live preview
    (() => {
        const range   = document.getElementById('club-logo-size-range');
        const badge   = document.getElementById('club-logo-size-badge');
        const hint    = document.getElementById('club-logo-size-hint');
        const preview = document.getElementById('club-logo-preview');
        if (!range) return;

        function applyClubSize(val) {
            const v = parseInt(val, 10);
            badge.textContent = v + ' px';
            if (hint) hint.textContent =
                `Navbar: ${v}px · Footer: ${Math.round(v * 0.71)}px · Loading screen: ${Math.round(v * 1.18)}px`;
            if (preview) {
                preview.style.width  = v + 'px';
                preview.style.height = v + 'px';
                preview.style.borderRadius = '50%';
                preview.style.objectFit   = 'cover';
                preview.style.maxWidth    = 'none';
            }
        }
        applyClubSize(range.value);
        range.addEventListener('input', () => applyClubSize(range.value));
    })();

    // Developer logo height slider — live preview
    (() => {
        const range   = document.getElementById('dev-logo-height-range');
        const badge   = document.getElementById('dev-logo-height-badge');
        const preview = document.getElementById('dev-logo-preview');
        if (!range) return;

        function applyDevHeight(val) {
            const h = parseInt(val, 10);
            badge.textContent = h + ' px';
            if (preview) {
                preview.style.height   = h + 'px';
                preview.style.width    = 'auto';
                preview.style.maxWidth = 'min(320px, 64vw)';
                preview.style.borderRadius = '6px';
                preview.style.objectFit    = 'contain';
            }
        }
        applyDevHeight(range.value);
        range.addEventListener('input', () => applyDevHeight(range.value));
    })();
</script>
@endsection
