@extends('layouts.admin')

@php
    $all = \App\Models\Setting::query()->get()->keyBy('key');
    $grouped = $all->values()->groupBy('group');
    $mail = $grouped->get('mail', collect())->keyBy('key');
    $valueOf = fn (string $key, string $default = ''): string => (string) (optional($all->get($key))->value ?? $default);

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
    @continue(in_array($groupName, ['mail', 'contact', 'social'], true))
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
</script>
@endsection
