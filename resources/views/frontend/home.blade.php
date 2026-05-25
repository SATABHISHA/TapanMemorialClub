@extends('layouts.frontend')

@section('content')
    {{-- ============== HERO ============== --}}
    <section class="hero-section">
        <div class="hero-bg-slider swiper">
            <div class="swiper-wrapper">
                @forelse($heroSliders as $slider)
                    @php
                        $hasWideSource = $slider->media && $slider->media->width && $slider->media->width >= 1200 && $slider->media->width >= $slider->media->height;
                        $bgImage = $hasWideSource
                            ? route('media.show', $slider->media_library_id)
                            : asset('assets/images/stadium-bg.jpg');
                    @endphp
                    <div class="swiper-slide hero-bg-slide" style="background-image: linear-gradient(125deg, rgba(122,15,36,.82) 0%, rgba(21,52,110,.78) 60%, rgba(3,8,26,.85) 100%), url('{{ $bgImage }}')"></div>
                @empty
                    <div class="swiper-slide hero-bg-slide" style="background-image: linear-gradient(125deg, rgba(122,15,36,.82) 0%, rgba(21,52,110,.78) 60%, rgba(3,8,26,.85) 100%), url('{{ asset('assets/images/stadium-bg.jpg') }}')"></div>
                @endforelse
            </div>
        </div>
        <div class="hero-grid"></div>
        <div class="hero-orbs">
            <span class="orb orb-gold"></span>
            <span class="orb orb-sky"></span>
            <span class="orb orb-maroon"></span>
        </div>

        <div class="container position-relative hero-content">
            <div class="row align-items-start align-items-lg-center hero-row">
                <div class="col-lg-7" data-aos="fade-right">
                    <p class="hero-chip"><span class="pulse-dot"></span> Franchise Mode · Season ’25</p>
                    <h1 class="hero-title">
                        Where <span class="word-gradient">Legacy</span> Meets
                        <br>
                        <span class="word-outline">League-Level</span>
                        <span class="word-gradient">Cricket</span>
                    </h1>
                    <p class="hero-sub">Tapan Memorial Club blends 80+ years of historical pride with modern analytics-driven cricket — proudly carrying the maroon-blue heart of Kolkata.</p>
                    <div class="d-flex gap-2 gap-md-3 flex-wrap mt-3 mt-lg-4 hero-cta-row">
                        <a href="#achievements" class="btn btn-gold btn-lg"><i class="bi bi-trophy-fill"></i> View Achievements</a>
                        <a href="#contact" class="btn btn-outline-gold btn-lg"><i class="bi bi-people-fill"></i> Join The Club</a>
                    </div>
                    <div class="count-grid mt-3 mt-lg-5">
                        <div class="count-card">
                            <i class="bi bi-calendar-heart text-warning"></i>
                            <h3 class="counter" data-count="1942">0</h3>
                            <span>Estd.</span>
                        </div>
                        <div class="count-card">
                            <i class="bi bi-flag-fill text-info"></i>
                            <h3 class="counter" data-count="{{ max($performances->count(), 12) }}">0</h3>
                            <span>Tournaments</span>
                        </div>
                        <div class="count-card">
                            <i class="bi bi-trophy text-warning"></i>
                            <h3 class="counter" data-count="{{ max($achievements->count(), 8) }}">0</h3>
                            <span>Achievements</span>
                        </div>
                        <div class="count-card">
                            <i class="bi bi-people-fill text-info"></i>
                            <h3 class="counter" data-count="60">0</h3>
                            <span>Players</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-5 mt-3 mt-lg-0" data-aos="fade-left">
                    <div class="hero-trophy-wrap">
                        <div class="trophy-glow"></div>
                        <div class="swiper hero-swiper">
                            <div class="swiper-wrapper">
                                @forelse($heroSliders as $slider)
                                    <div class="swiper-slide">
                                        <div class="hero-card glass-card">
                                            @if($slider->media_library_id)
                                                <div class="hero-card-media">
                                                    <img
                                                        src="{{ route('media.show', $slider->media_library_id) }}"
                                                        alt="{{ $slider->title }}"
                                                        @if($loop->first) fetchpriority="high" loading="eager" @else loading="lazy" @endif
                                                        decoding="async"
                                                    >
                                                </div>
                                            @endif
                                            <div class="hero-card-body">
                                                <span class="hero-card-tag"><i class="bi bi-stars text-warning"></i> Featured</span>
                                                <h4>{{ $slider->title }}</h4>
                                                <p>{{ $slider->subtitle }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="swiper-slide">
                                        <div class="hero-card glass-card p-4">
                                            <h4>Tapan Memorial Club</h4>
                                            <p>Add sliders from admin panel to animate this premium hero zone.</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hero-scroll-hint">
            <span></span><span></span><span></span>
            <small>Scroll</small>
        </div>
    </section>

    {{-- ============== INTRODUCTION / CLUB STORY ============== --}}
    @php
        $fullStory = trim((string) ($introText ?? ''));
        if ($fullStory === '') {
            $fullStory = 'From grassroots dedication to stadium-grade ambition, Tapan Memorial Club has shaped a proud cricketing identity since 1942.';
        }
        $storyParagraphs = preg_split('/\r?\n\s*\r?\n|\r?\n/u', $fullStory);
        $storyParagraphs = array_values(array_filter(array_map('trim', $storyParagraphs ?: [])));
        $teaserText = \Illuminate\Support\Str::limit($storyParagraphs[0] ?? $fullStory, 240);
        $hasMore = count($storyParagraphs) > 1 || mb_strlen($fullStory) > mb_strlen($teaserText);
    @endphp
    <section class="section-pad position-relative" id="introduction">
        <div class="section-blob blob-1"></div>
        <div class="container position-relative">
            <div class="section-head text-center" data-aos="fade-up">
                <span class="eyebrow">Our Heritage</span>
                <h2>The Club <span class="gradient-text">Story</span></h2>
                <p>Eighty-plus years of bat, ball, and brotherhood — woven into the maroon-and-blue fabric of Kolkata cricket.</p>
            </div>

            <div class="row g-4 mt-3">
                <div class="col-lg-4" data-aos="zoom-in">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-bricks"></i></div>
                        <h5>Heritage Foundation</h5>
                        <p>Built on discipline, community pride, and generations of local cricket culture.</p>
                        <span class="feature-stat">80+ Years</span>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="zoom-in" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-graph-up-arrow"></i></div>
                        <h5>Competitive Growth</h5>
                        <p>Competing across leagues with a focused, analytics-first approach and strong team cohesion.</p>
                        <span class="feature-stat">Top-Tier League</span>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="zoom-in" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-stars"></i></div>
                        <h5>Future Vision</h5>
                        <p>Creating a youth-driven high-performance ecosystem with modern facilities and smart coaching.</p>
                        <span class="feature-stat">Vision 2030</span>
                    </div>
                </div>
            </div>

            <div class="story-card mt-5" data-aos="fade-up">
                <div class="story-grid">
                    <aside class="story-aside">
                        <h3 class="story-year">1942</h3>
                        <span class="story-year-sub">The Monsoon Beginning</span>
                        <div class="story-meta">
                            <span class="story-meta-item"><i class="bi bi-calendar-event"></i> Founded 1942</span>
                            <span class="story-meta-item"><i class="bi bi-shield-check"></i> CAB Affiliated 1956</span>
                            <span class="story-meta-item"><i class="bi bi-graph-up"></i> Senior Division</span>
                            <span class="story-meta-item"><i class="bi bi-trophy"></i> Super League Regulars</span>
                        </div>

                        <div class="aside-journey" aria-hidden="true">
                            <span class="journey-title"><i class="bi bi-stars"></i> 84 Years Of Glory</span>
                            <div class="journey-rail">
                                <span class="rail-line"></span>
                                <div class="rail-node" style="--d:0s"><span class="dot"></span><b>1942</b><i>Founded · Entally</i></div>
                                <div class="rail-node" style="--d:.4s"><span class="dot"></span><b>1956</b><i>CAB Affiliation</i></div>
                                <div class="rail-node" style="--d:.8s"><span class="dot"></span><b>1969</b><i>Senior Division</i></div>
                                <div class="rail-node" style="--d:1.2s"><span class="dot"></span><b>2005</b><i>Podium Era</i></div>
                                <div class="rail-node" style="--d:1.6s"><span class="dot"></span><b>2015</b><i>Super League</i></div>
                                <div class="rail-node" style="--d:2s"><span class="dot pulse"></span><b>Today</b><i>The Saga Continues</i></div>
                            </div>

                            <div class="aside-stats">
                                <div class="stat-orb">
                                    <svg viewBox="0 0 64 64"><circle cx="32" cy="32" r="28" class="ring-bg"/><circle cx="32" cy="32" r="28" class="ring-fg" style="--p:88"/></svg>
                                    <div class="orb-val"><b>84+</b><span>Years</span></div>
                                </div>
                                <div class="stat-orb">
                                    <svg viewBox="0 0 64 64"><circle cx="32" cy="32" r="28" class="ring-bg"/><circle cx="32" cy="32" r="28" class="ring-fg gold" style="--p:72"/></svg>
                                    <div class="orb-val"><b>15+</b><span>Trophies</span></div>
                                </div>
                                <div class="stat-orb">
                                    <svg viewBox="0 0 64 64"><circle cx="32" cy="32" r="28" class="ring-bg"/><circle cx="32" cy="32" r="28" class="ring-fg sky" style="--p:95"/></svg>
                                    <div class="orb-val"><b>200+</b><span>Players</span></div>
                                </div>
                            </div>

                            <div class="aside-crest">
                                <span class="crest-ring"></span>
                                <span class="crest-ring delay"></span>
                                <span class="crest-core"><i class="bi bi-shield-fill-check"></i></span>
                            </div>
                        </div>
                    </aside>

                    <div class="story-main">
                        <span class="story-quote"><i class="bi bi-quote"></i> A Story That Began In The Monsoon</span>
                        <p class="story-teaser">
                            <span class="story-teaser-lead">{{ mb_substr($teaserText, 0, 1) }}</span>{{ mb_substr($teaserText, 1) }}
                        </p>

                        @if($hasMore)
                            <div class="story-full" id="storyFull" aria-hidden="true">
                                <div class="story-full-inner">
                                    <div class="story-body">
                                        @foreach($storyParagraphs as $i => $para)
                                            @if($i === 0) @continue @endif
                                            <p>{{ $para }}</p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="story-toggle" data-story-toggle aria-expanded="false" aria-controls="storyFull">
                                <span data-label-more>Read Full Story</span>
                                <span data-label-less hidden>Collapse Story</span>
                                <i class="bi bi-chevron-down"></i>
                            </button>
                        @endif

                        {{-- Heritage spotlight panel fills empty space when collapsed --}}
                        <div class="story-monsoon" aria-hidden="true">
                            <div class="heritage-stage">
                                <span class="stage-ambient-glow"></span>
                                <span class="stage-grid"></span>
                                <span class="light-beam beam-1"></span>
                                <span class="light-beam beam-2"></span>
                                <span class="light-beam beam-3"></span>

                                <div class="heritage-centerpiece">
                                    <span class="year-pill">1942</span>
                                    <h4>Monsoon Genesis</h4>
                                    <p>Rain-soaked beginnings that forged a championship culture.</p>
                                </div>

                                <div class="heritage-track" role="presentation">
                                    <span class="track-line"></span>
                                    <div class="track-stop"><i class="bi bi-flag"></i><small>Founded</small></div>
                                    <div class="track-stop"><i class="bi bi-shield-check"></i><small>Affiliated</small></div>
                                    <div class="track-stop"><i class="bi bi-graph-up-arrow"></i><small>Senior Rise</small></div>
                                    <div class="track-stop"><i class="bi bi-trophy"></i><small>League Force</small></div>
                                </div>

                                <div class="legacy-panels">
                                    <article class="legacy-panel">
                                        <span class="panel-kicker">Origin</span>
                                        <h6>Monsoon Camp</h6>
                                        <p>Local boys turned rain delays into training drills.</p>
                                    </article>
                                    <article class="legacy-panel">
                                        <span class="panel-kicker">Identity</span>
                                        <h6>Maroon & Gold</h6>
                                        <p>Built on grit, respect, and relentless match temperament.</p>
                                    </article>
                                    <article class="legacy-panel">
                                        <span class="panel-kicker">Legacy</span>
                                        <h6>Tapan Memorial Club</h6>
                                        <p>A neighborhood team that evolved into a feared unit.</p>
                                    </article>
                                </div>

                                <span class="monsoon-caption">Where The Story Began · Monsoon · 1942</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============== ACHIEVEMENTS ============== --}}
    <section class="section-pad bg-deep position-relative" id="achievements">
        <div class="section-blob blob-2"></div>
        <div class="container position-relative">
            <div class="section-head text-center" data-aos="fade-up">
                <span class="eyebrow">Trophy Cabinet</span>
                <h2>Achievement <span class="gradient-text">Wall</span></h2>
                <p>Milestones that define our hunger for the game — every trophy a story of grit.</p>
            </div>
            <div class="row g-4">
                @foreach($achievements as $achievement)
                    <div class="col-md-6 col-xl-3" data-aos="flip-left" data-aos-delay="{{ $loop->index * 80 }}">
                        <article class="achievement-card">
                            <div class="achievement-icon"><i class="bi bi-trophy-fill"></i></div>
                            <span class="achievement-year">{{ $achievement->year ?? optional($achievement->achievement_date)->format('Y') }}</span>
                            <h5>{{ $achievement->title }}</h5>
                            <p>{{ $achievement->description }}</p>
                            <div class="achievement-shine"></div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============== GALLERY ============== --}}
    <section class="section-pad position-relative" id="gallery">
        <div class="section-blob blob-3"></div>
        <div class="container position-relative">
            <div class="section-head text-center" data-aos="fade-up">
                <span class="eyebrow">Visual Diary</span>
                <h2>Players &amp; <span class="gradient-text">Gallery</span></h2>
                <p>Glimpses from match days, trophy celebrations, and training grind.</p>
            </div>
            <div class="gallery-grid">
                @foreach($gallery as $item)
                    @php
                        // Masonry variation: every 7th = feature, every 5th = tall, every 4th = wide
                        $i = $loop->index;
                        $variant = '';
                        if ($i % 7 === 0 && $i !== 0) { $variant = 'feature'; }
                        elseif ($i % 5 === 0 && $i !== 0) { $variant = 'tall'; }
                        elseif ($i % 4 === 0 && $i !== 0) { $variant = 'wide'; }
                    @endphp
                    <a href="{{ $item->media_library_id ? route('media.show', $item->media_library_id) : '#' }}" class="gallery-tile {{ $variant }}" data-aos="zoom-in" data-aos-delay="{{ ($loop->index % 6) * 60 }}">
                        @if($item->media_library_id)
                            <span class="gallery-backdrop" style="background-image: url('{{ route('media.thumb', $item->media_library_id) }}')" aria-hidden="true"></span>
                            <img loading="lazy" decoding="async" fetchpriority="low" src="{{ route('media.show', $item->media_library_id) }}" alt="{{ $item->title }}">
                        @else
                            <div class="gallery-placeholder"><i class="bi bi-image-alt"></i></div>
                        @endif
                        <div class="gallery-overlay">
                            <i class="bi bi-arrows-fullscreen"></i>
                            <span>{{ $item->title ?: 'TM Club' }}</span>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Players spotlight slider — every image shown in full --}}
            @if($gallery->count() > 0)
                <div class="players-spotlight mt-5" data-aos="fade-up">
                    <div class="spotlight-head">
                        <span class="eyebrow"><i class="bi bi-camera-reels"></i> Spotlight Reel</span>
                        <h3>Every Frame, <span class="gradient-text">Every Player</span></h3>
                    </div>
                    <div class="swiper players-swiper">
                        <div class="swiper-wrapper">
                            @foreach($gallery as $item)
                                <div class="swiper-slide">
                                    <article class="spotlight-card">
                                        <div class="spotlight-media">
                                            <img loading="lazy" src="{{ route('media.show', $item->media_library_id) }}" alt="{{ $item->title }}">
                                            <div class="spotlight-blur" style="background-image: url('{{ route('media.thumb', $item->media_library_id) }}')"></div>
                                        </div>
                                        <div class="spotlight-meta">
                                            <span class="spotlight-tag">{{ strtoupper($item->category ?? 'TMC') }}</span>
                                            <h5>{{ $item->title ?: 'Tapan Memorial Club' }}</h5>
                                            <small>Frame #{{ str_pad((string)($loop->index + 1), 2, '0', STR_PAD_LEFT) }} · TMC Archive</small>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        </div>
                        <div class="players-pagination"></div>
                        <button class="spotlight-nav prev" type="button" aria-label="Previous"><i class="bi bi-chevron-left"></i></button>
                        <button class="spotlight-nav next" type="button" aria-label="Next"><i class="bi bi-chevron-right"></i></button>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- ============== NEWS / VLOGS ============== --}}
    <section class="section-pad bg-deep" id="news" data-section-reveal>
        <div class="container">
            <div class="section-head text-center" data-aos="fade-up">
                <span class="eyebrow">Pavilion Buzz</span>
                <h2>News &amp; <span class="gradient-text">Vlogs</span></h2>
            </div>
            <div class="row g-4">
                @foreach($blogs as $blog)
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                        <article class="news-card">
                            <div class="news-card-icon"><i class="bi bi-megaphone-fill"></i></div>
                            <h5>{{ $blog->title }}</h5>
                            <p>{{ $blog->excerpt }}</p>
                            @if($blog->youtube_url)
                                <a href="{{ route('blogs.show', $blog->slug) }}" class="btn btn-sm btn-gold"><i class="bi bi-play-circle-fill"></i> Watch</a>
                            @else
                                <a href="{{ route('blogs.show', $blog->slug) }}" class="news-link">Read more <i class="bi bi-arrow-right"></i></a>
                            @endif
                        </article>
                    </div>
                @endforeach
            </div>

            @if(isset($vlogs) && $vlogs->count() > 0)
                <div class="d-flex justify-content-between align-items-end mt-5 mb-3" data-aos="fade-up">
                    <div>
                        <span class="eyebrow"><i class="bi bi-camera-reels"></i> Latest Vlogs</span>
                        <h3 class="mb-0 text-white">From The <span class="gradient-text">Dressing Room</span></h3>
                    </div>
                    <a href="{{ route('vlogs.index') }}" class="btn btn-outline-light btn-sm">All Vlogs <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="row g-4">
                    @foreach($vlogs as $vlog)
                        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 70 }}">
                            <a href="{{ route('vlogs.show', $vlog->slug) }}" class="vlog-card glass-card d-block h-100 text-decoration-none overflow-hidden">
                                <div class="vlog-media">
                                    @if($vlog->image_media_id)
                                        <img src="{{ route('media.thumb', $vlog->image_media_id) }}" alt="{{ $vlog->title }}" loading="lazy" decoding="async" fetchpriority="low">
                                    @else
                                        <div class="vlog-fallback"><i class="bi bi-play-btn"></i></div>
                                    @endif
                                    @if($vlog->video_url)<span class="vlog-play"><i class="bi bi-play-fill"></i></span>@endif
                                    @if($vlog->is_featured)<span class="vlog-badge">Featured</span>@endif
                                </div>
                                <div class="p-3">
                                    @if($vlog->menu)<span class="vlog-tag">{{ $vlog->menu->title }}</span>@endif
                                    <h5 class="text-white mt-2 mb-2">{{ $vlog->title }}</h5>
                                    <p class="text-white-50 small mb-2">{{ \Illuminate\Support\Str::limit($vlog->description, 110) }}</p>
                                    <small class="text-warning">{{ optional($vlog->published_at)->format('d M Y') }}</small>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ============== LIVE PERFORMANCE ============== --}}
    <section class="section-pad position-relative" id="live-performance" data-section-reveal>
        <div class="section-blob blob-4"></div>
        <div class="container position-relative">
            <div class="section-head text-center" data-aos="fade-up">
                <span class="eyebrow">Data Pavilion</span>
                <h2>Live Performance <span class="gradient-text">Analytics</span></h2>
                <p>Wins, points, and momentum tracked across our most recent campaigns.</p>
            </div>

            <div class="row g-4 perf-row">
                <div class="col-md-3" data-aos="fade-up">
                    <div class="perf-stat">
                        <i class="bi bi-trophy-fill"></i>
                        <h3 class="counter" data-count="{{ $performances->sum('wins') ?: 13 }}">0</h3>
                        <span>Total Wins</span>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="80">
                    <div class="perf-stat">
                        <i class="bi bi-bullseye"></i>
                        <h3 class="counter" data-count="{{ $performances->sum('points') ?: 26 }}">0</h3>
                        <span>Points Won</span>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="160">
                    <div class="perf-stat">
                        <i class="bi bi-fire"></i>
                        <h3 class="counter" data-count="{{ $performances->sum('matches_played') ?: 17 }}">0</h3>
                        <span>Matches</span>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="240">
                    <div class="perf-stat">
                        <i class="bi bi-graph-up"></i>
                        <h3 class="counter" data-count="76">0</h3>
                        <span>% Win Rate</span>
                    </div>
                </div>
            </div>

            <div class="performance-card mt-4 perf-pavilion" data-aos="zoom-in">
                {{-- Scoreboard top strip --}}
                <div class="perf-scoreboard">
                    <div class="perf-scoreboard__crest">
                        <i class="bi bi-shield-fill-check"></i>
                    </div>
                    <div class="perf-scoreboard__title">
                        <span class="perf-scoreboard__eyebrow">Match Ledger</span>
                        <h4 class="mb-0">Tournament <span class="text-gold">Performance</span></h4>
                    </div>
                    <div class="perf-scoreboard__legend">
                        <span class="perf-chip perf-chip--sky"><span class="dot"></span> Wins</span>
                        <span class="perf-chip perf-chip--gold"><span class="dot"></span> Points</span>
                    </div>
                </div>

                {{-- Glowing chart wrapper --}}
                <div class="performance-chart-wrap perf-chart-wrap--glow">
                    <div class="perf-chart-corner perf-chart-corner--tl"></div>
                    <div class="perf-chart-corner perf-chart-corner--tr"></div>
                    <div class="perf-chart-corner perf-chart-corner--bl"></div>
                    <div class="perf-chart-corner perf-chart-corner--br"></div>
                    <canvas id="performanceChart"></canvas>
                </div>

                {{-- Trophy ticker — auto-built from performances --}}
                @if($performances && $performances->count())
                    <div class="perf-ticker">
                        <div class="perf-ticker__track">
                            @foreach($performances->concat($performances) as $row)
                                <span class="perf-ticker__item">
                                    <i class="bi bi-trophy-fill"></i>
                                    <strong>{{ $row->year }}</strong>
                                    {{ $row->tournament }}
                                    @if($row->position)<em class="perf-ticker__pos">— {{ $row->position }}</em>@endif
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(!empty($performanceText))
                    @php
                        // Split the recap text into per-season cards by matching "YYYY-YY :" / "YYYY:" markers.
                        $seasonChunks = [];
                        $pattern = '/(\d{4}(?:[-\/]\d{2,4})?)\s*[:：]\s*/u';
                        if (preg_match_all($pattern, $performanceText, $m, PREG_OFFSET_CAPTURE)) {
                            $matches = $m[0];
                            $years = $m[1];
                            for ($i = 0; $i < count($matches); $i++) {
                                $start = $matches[$i][1] + strlen($matches[$i][0]);
                                $end = $i + 1 < count($matches) ? $matches[$i + 1][1] : strlen($performanceText);
                                $chunk = trim(substr($performanceText, $start, $end - $start));
                                if ($chunk === '') { continue; }
                                // Split items by en-dash / em-dash / hyphen-spaced.
                                $items = preg_split('/\s*[–—-]\s+/u', $chunk);
                                $items = array_values(array_filter(array_map('trim', $items), fn($s) => $s !== ''));
                                $seasonChunks[] = ['year' => trim($years[$i][0]), 'items' => $items];
                            }
                        }
                    @endphp

                    <div class="perf-recap-wrap" data-aos="fade-up">
                        <div class="perf-recap__masthead">
                            <span class="perf-recap__ribbon"><i class="bi bi-journal-richtext"></i> Performance Recap</span>
                            <span class="perf-recap__stamp">Est. 1942 · TMC Archives</span>
                        </div>

                        @if(count($seasonChunks))
                            <div class="perf-season-grid">
                                @foreach($seasonChunks as $idx => $season)
                                    <article class="perf-season-card">
                                        <header class="perf-season-card__head">
                                            <span class="perf-season-card__year">{{ $season['year'] }}</span>
                                            <i class="bi bi-trophy-fill perf-season-card__icon"></i>
                                        </header>
                                        <ul class="perf-season-card__list">
                                            @foreach($season['items'] as $item)
                                                <li><i class="bi bi-chevron-right"></i><span>{{ $item }}</span></li>
                                            @endforeach
                                        </ul>
                                    </article>
                                @endforeach
                            </div>
                        @else
                            {{-- Fallback: unparseable text — render as newspaper paragraph --}}
                            <div class="perf-recap-scroll">
                                <p class="perf-recap__body mb-0">{{ $performanceText }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ============== SPONSORS ============== --}}
    <section class="section-pad bg-deep" id="sponsors">
        <div class="container">
            <div class="section-head text-center" data-aos="fade-up">
                <span class="eyebrow">Backed By</span>
                <h2 class="sponsor-title">
                    <span class="sponsor-title__plain">Our</span>
                    <span class="sponsor-title__accent">Sponsors</span>
                </h2>
            </div>
            <div class="sponsor-marquee" data-aos="fade-up">
                <div class="track">
                    @foreach($sponsors as $sponsor)
                        <div class="sponsor-pill"><i class="bi bi-patch-check-fill text-warning"></i> {{ $sponsor->name }}</div>
                    @endforeach
                    @foreach($sponsors as $sponsor)
                        <div class="sponsor-pill"><i class="bi bi-patch-check-fill text-warning"></i> {{ $sponsor->name }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ============== CONTACT ============== --}}
    <section class="section-pad" id="contact">
        @php
            $contactLat = trim((string) ($siteConfig['contact_latitude'] ?? ''));
            $contactLng = trim((string) ($siteConfig['contact_longitude'] ?? ''));
            $hasCoords = is_numeric($contactLat) && is_numeric($contactLng)
                && (float) $contactLat >= -90 && (float) $contactLat <= 90
                && (float) $contactLng >= -180 && (float) $contactLng <= 180;

            $contactMap = $hasCoords
                ? 'https://www.google.com/maps?q='.$contactLat.','.$contactLng.'&z=15&output=embed'
                : ($siteConfig['contact_map_embed_url'] ?? 'https://www.google.com/maps?q=Kolkata&output=embed');
            $contactAddress = $siteConfig['contact_address'] ?? '';
            $contactPhone = $siteConfig['contact_phone'] ?? '';
            $contactEmail = $siteConfig['contact_email'] ?? '';
        @endphp
        <div class="container">
            <div class="section-head text-center" data-aos="fade-up">
                <span class="eyebrow">Get In Touch</span>
                <h2>Contact <span class="gradient-text">Club Desk</span></h2>
            </div>
            <div class="row g-4">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="glass-card p-3 h-100">
                        <iframe class="map-frame" src="{{ $contactMap }}" loading="lazy"></iframe>
                        <div class="pt-3 px-2">
                            @if(filled($contactAddress))
                                <p class="mb-1 text-light-emphasis"><i class="bi bi-geo-alt-fill text-warning me-2"></i>{{ $contactAddress }}</p>
                            @endif
                            @if(filled($contactPhone))
                                <p class="mb-1 text-light-emphasis"><i class="bi bi-telephone-fill text-warning me-2"></i>{{ $contactPhone }}</p>
                            @endif
                            @if(filled($contactEmail))
                                <p class="mb-0 text-light-emphasis"><i class="bi bi-envelope-fill text-warning me-2"></i>{{ $contactEmail }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <form method="POST" action="{{ route('contact.store') }}" class="glass-card p-4 h-100">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6"><input class="form-control" name="name" placeholder="Your Name" required></div>
                            <div class="col-md-6"><input class="form-control" type="email" name="email" placeholder="Email Address" required></div>
                            <div class="col-md-6"><input class="form-control" name="phone" placeholder="Phone"></div>
                            <div class="col-md-6"><input class="form-control" name="subject" placeholder="Subject"></div>
                            <div class="col-12"><textarea class="form-control" rows="4" name="message" placeholder="Your Message" required></textarea></div>
                            <div class="col-12"><button class="btn btn-gold w-100 btn-lg"><i class="bi bi-send-fill"></i> Send Message</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
