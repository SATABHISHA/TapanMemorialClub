<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Tapan Memorial Club — Legacy. League. Champions.' }}</title>
    <link rel="icon" href="{{ asset('assets/images/logo.jpeg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="tmc-body">
    @php
        $contactAddress = (string) ($siteConfig['contact_address'] ?? '');
        $contactPhone = (string) ($siteConfig['contact_phone'] ?? '');
        $contactEmail = (string) ($siteConfig['contact_email'] ?? '');
        $whatsAppDigits = preg_replace('/\D+/', '', $siteConfig['contact_whatsapp_number'] ?? '');
        $socialLinks = [
            ['url' => $siteConfig['social_instagram_url'] ?? '', 'icon' => 'bi-instagram'],
            ['url' => $siteConfig['social_facebook_url'] ?? '', 'icon' => 'bi-facebook'],
            ['url' => $siteConfig['social_youtube_url'] ?? '', 'icon' => 'bi-youtube'],
            ['url' => $siteConfig['social_twitter_url'] ?? '', 'icon' => 'bi-twitter-x'],
            ['url' => $siteConfig['social_linkedin_url'] ?? '', 'icon' => 'bi-linkedin'],
        ];
        $socialLinks = array_values(array_filter($socialLinks, fn ($link) => filled($link['url'])));
        $developerBrandName = trim((string) ($siteConfig['developer_brand_name'] ?? 'AhaNova AI Technologies Pvt. Ltd.'));
        $developerLogoUrl = trim((string) ($siteConfig['developer_logo_url'] ?? ''));
        $developerWebsiteUrl = trim((string) ($siteConfig['developer_website_url'] ?? ''));
        if ($developerLogoUrl === '' && file_exists(public_path('assets/images/ahanova-logo.png'))) {
            $developerLogoUrl = asset('assets/images/ahanova-logo.png');
        }
        $pageMenus = $globalMenus->filter(fn ($menu) => str_starts_with((string) ($menu->url ?? ''), '/pages/'))->values();
        $siteMenus = $globalMenus->reject(fn ($menu) => str_starts_with((string) ($menu->url ?? ''), '/pages/'))->values();
    @endphp

    <div id="preloader" class="tmc-preloader">
        <div class="preloader-ring">
            <img src="{{ asset('assets/images/logo.jpeg') }}" alt="Tapan Memorial Club" class="tmc-preloader-logo">
        </div>
        <p class="preloader-text">Loading Franchise Experience<span class="dots"></span></p>
    </div>

    <div class="cursor-glow"></div>
    <div class="progress-wrap"><span id="scroll-progress"></span></div>

    <!-- Top utility bar -->
    <div class="tmc-topbar d-none d-lg-block">
        <div class="container d-flex justify-content-between align-items-center small">
            <div class="d-flex gap-3">
                @if(filled($contactAddress))
                    <span><i class="bi bi-geo-alt-fill text-warning"></i> {{ $contactAddress }}</span>
                @endif
                @if(filled($contactPhone))
                    <span><i class="bi bi-telephone-fill text-warning"></i> {{ $contactPhone }}</span>
                @endif
                @if(filled($contactEmail))
                    <span><i class="bi bi-envelope-fill text-warning"></i> {{ $contactEmail }}</span>
                @endif
            </div>
            <div class="d-flex gap-3 align-items-center">
                <span class="text-warning">Estd. 1942</span>
                @foreach($socialLinks as $social)
                    <a href="{{ $social['url'] }}" class="text-light" target="_blank" rel="noopener"><i class="bi {{ $social['icon'] }}"></i></a>
                @endforeach
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand sticky-top tmc-navbar tmc-navbar--compact">
        <div class="container py-2 d-flex align-items-center flex-nowrap gap-2">
            <a class="navbar-brand brand-lockup flex-shrink-0" href="{{ route('home') }}">
                <img src="{{ asset('assets/images/logo.jpeg') }}" alt="TMC" class="brand-logo">
                <div class="brand-lockup__text">
                    <span class="brand-text">Tapan Memorial Club</span>
                    <small class="brand-sub d-none d-sm-block">Legacy · League · Champions</small>
                </div>
            </a>
            <ul class="navbar-nav ms-auto align-items-center flex-row gap-2 mb-0">
                @if($pageMenus->count())
                    <li class="nav-item dropdown tmc-dropdown-hover">
                        <button type="button" class="nav-link dropdown-toggle tmc-club-pages-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="tmc-club-pages-toggle__icon"><i class="bi bi-list"></i></span>
                            <span class="d-none d-sm-inline">Club Pages</span>
                            <span class="tmc-nav-count">{{ $pageMenus->count() }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end tmc-dropdown tmc-pages-dropdown tmc-pages-dropdown--animated">
                            @foreach($pageMenus as $menu)
                                @php
                                    $pageMenuUrl = $menu->url ?: '#';
                                    $pageMenuHref = \Illuminate\Support\Str::startsWith($pageMenuUrl, '#') ? route('home') . $pageMenuUrl : $pageMenuUrl;
                                @endphp
                                <li>
                                    <a class="dropdown-item tmc-page-item" href="{{ $pageMenuHref }}">
                                        <span class="tmc-page-item__icon">
                                            @if($menu->icon)
                                                <i class="bi {{ $menu->icon }}"></i>
                                            @else
                                                <i class="bi bi-stars"></i>
                                            @endif
                                        </span>
                                        <span>{{ $menu->title }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif

            </ul>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    @if(filled($whatsAppDigits))
        <a href="https://wa.me/{{ $whatsAppDigits }}" class="whatsapp-float" target="_blank" rel="noopener" title="Chat on WhatsApp">
            <i class="bi bi-whatsapp"></i>
        </a>
    @endif

    <footer class="tmc-footer">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="footer-brand mb-3">
                        <img src="{{ asset('assets/images/logo.jpeg') }}" class="footer-logo" alt="Logo">
                        <div class="footer-brand__text">
                            <h4 class="gradient-text mb-0">Tapan Memorial Club</h4>
                            <span class="footer-brand__sub">Legacy · League · Champions</span>
                        </div>
                    </div>
                    <p class="text-light-emphasis">Estd. 1942 · Legacy, League Spirit, and Modern Cricket Excellence rooted in the maroon-blue heart of Kolkata.</p>
                    <div class="d-flex gap-2">
                        @foreach($socialLinks as $social)
                            <a href="{{ $social['url'] }}" class="social-pill" target="_blank" rel="noopener"><i class="bi {{ $social['icon'] }}"></i></a>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-4">
                    <h6 class="text-uppercase text-warning mb-3">Quick Links</h6>
                    <div class="quick-links-grid">
                        @foreach($siteMenus->take(8) as $m)
                            @php
                                $quickUrl = $m->url ?: '#';
                                $quickHref = \Illuminate\Support\Str::startsWith($quickUrl, '#') ? route('home') . $quickUrl : $quickUrl;
                            @endphp
                            <a href="{{ $quickHref }}" class="footer-link footer-link--plain">
                                @if($m->icon)<i class="bi {{ $m->icon }}"></i>@else<i class="bi bi-link-45deg"></i>@endif
                                <span>{{ $m->title }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-4">
                    <h6 class="text-uppercase text-warning mb-3">Contact Desk</h6>
                    @if(filled($contactAddress))
                        <p class="mb-1 text-light-emphasis"><i class="bi bi-geo-alt-fill text-warning me-2"></i> {{ $contactAddress }}</p>
                    @endif
                    @if(filled($contactPhone))
                        <p class="mb-1 text-light-emphasis"><i class="bi bi-telephone-fill text-warning me-2"></i> {{ $contactPhone }}</p>
                    @endif
                    @if(filled($contactEmail))
                        <p class="mb-0 text-light-emphasis"><i class="bi bi-envelope-fill text-warning me-2"></i> {{ $contactEmail }}</p>
                    @endif
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="d-flex flex-wrap justify-content-between gap-2 small text-light-emphasis">
                <span>&copy; {{ date('Y') }} Tapan Memorial Club. All rights reserved.</span>
                <span>Crafted with <span class="text-danger">♥</span> for the love of cricket.</span>
                <a
                    href="{{ $developerWebsiteUrl !== '' ? $developerWebsiteUrl : '#' }}"
                    class="tmc-brand-signature"
                    @if($developerWebsiteUrl !== '') target="_blank" rel="noopener" @endif
                    aria-label="{{ $developerBrandName }}"
                >
                    @if($developerLogoUrl !== '')
                        <img src="{{ $developerLogoUrl }}" alt="{{ $developerBrandName }} logo" class="tmc-brand-signature__logo" loading="lazy" decoding="async">
                    @else
                        <span class="tmc-brand-signature__glyph" aria-hidden="true">A</span>
                    @endif
                    <strong class="tmc-brand-signature__name">{{ $developerBrandName }}</strong>
                </a>
            </div>
        </div>
    </footer>
</body>
</html>
