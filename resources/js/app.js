import './bootstrap';

import Alpine from 'alpinejs';
import * as bootstrap from 'bootstrap';
import 'bootstrap/dist/js/bootstrap.bundle';
import AOS from 'aos';

window.Alpine = Alpine;
window.bootstrap = bootstrap;
Alpine.start();

/* ============ DETAIL PAGE SCROLL RESET ============ */
if ('scrollRestoration' in history) {
	history.scrollRestoration = 'manual';
}

const shouldResetDetailScroll = document.querySelector('.blog-detail, .vlog-detail') && !window.location.hash;
if (shouldResetDetailScroll) {
	const resetToTop = () => window.scrollTo({ top: 0, left: 0, behavior: 'auto' });
	window.addEventListener('pageshow', resetToTop, { once: true });
	window.requestAnimationFrame(resetToTop);
}

/* ============ PERF: disable heavy AOS in lower sections ============ */
document
	.querySelectorAll('#news [data-aos], #live-performance [data-aos], #sponsors [data-aos], #contact [data-aos]')
	.forEach((el) => {
		el.removeAttribute('data-aos');
		el.removeAttribute('data-aos-delay');
		el.removeAttribute('data-aos-duration');
	});

/* ============ PRELOADER ============ */
window.addEventListener('load', () => {
	const preloader = document.getElementById('preloader');
	if (preloader) {
		setTimeout(() => preloader.classList.add('is-hidden'), 400);
	}
});

/* ============ AOS ============ */
AOS.init({ duration: 900, once: true, easing: 'ease-out-cubic', offset: 60 });

/* ============ SHARED HELPERS ============ */
const observeOnce = (el, callback, options = { threshold: 0.15 }) => {
	if (!el) return;
	const io = new IntersectionObserver((entries) => {
		entries.forEach((entry) => {
			if (!entry.isIntersecting) return;
			io.disconnect();
			callback();
		});
	}, options);
	io.observe(el);
};

let swiperBundlePromise;
const loadSwiperBundle = () => {
	if (!swiperBundlePromise) {
		swiperBundlePromise = Promise.all([
			import('swiper'),
			import('swiper/modules'),
		]).then(([swiperPkg, modulesPkg]) => ({
			Swiper: swiperPkg.Swiper,
			...modulesPkg,
		}));
	}
	return swiperBundlePromise;
};

/* ============ HERO BACKGROUND SLIDER ============ */
const heroBgEl = document.querySelector('.hero-bg-slider');
const heroSwiperEl = document.querySelector('.hero-swiper');
if (heroBgEl || heroSwiperEl) {
	loadSwiperBundle().then(({ Swiper, Autoplay, EffectFade, EffectCreative, Pagination, Keyboard }) => {
		if (heroBgEl) {
			new Swiper(heroBgEl, {
				modules: [Autoplay, EffectFade],
				loop: true,
				effect: 'fade',
				fadeEffect: { crossFade: true },
				speed: 1800,
				autoplay: { delay: 6000, disableOnInteraction: false },
				allowTouchMove: false,
			});
		}

		if (heroSwiperEl) {
			new Swiper(heroSwiperEl, {
				modules: [Autoplay, EffectCreative, Pagination, Keyboard],
				loop: true,
				effect: 'creative',
				creativeEffect: {
					limitProgress: 2,
					prev: {
						translate: ['-18%', 0, -220],
						opacity: 0,
						scale: 0.92,
					},
					next: {
						translate: ['18%', 0, -220],
						opacity: 0,
						scale: 0.92,
					},
				},
				speed: 1050,
				pagination: { el: '.swiper-pagination', clickable: true, dynamicBullets: true },
				keyboard: { enabled: true },
				autoplay: { delay: 4200, disableOnInteraction: false, pauseOnMouseEnter: true },
				observer: true,
				observeParents: true,
			});
		}
	}).catch(() => null);
}

/* ============ HERO CARD SLIDER ============ */
/* ============ HERO TEXT GSAP ============ */
if (document.querySelector('.hero-title')) {
	import('gsap').then(({ default: gsap }) => {
		gsap.from('.hero-chip', { y: -30, opacity: 0, duration: 1, ease: 'power3.out' });
		gsap.from('.hero-title', { y: 60, opacity: 0, duration: 1.2, delay: 0.15, ease: 'power3.out' });
		gsap.from('.hero-sub', { y: 30, opacity: 0, duration: 1, delay: 0.4 });
		gsap.from('.hero-content .btn', { y: 20, opacity: 0, duration: 0.8, delay: 0.6, stagger: 0.1 });
		gsap.from('.count-card', { y: 30, opacity: 0, duration: 0.8, delay: 0.8, stagger: 0.12, ease: 'back.out(1.4)' });
	}).catch(() => null);
}

/* ============ COUNTERS (intersection-driven) ============ */
const counters = document.querySelectorAll('.counter');
const animateCounter = (el) => {
	if (el.dataset.done === '1') return;
	el.dataset.done = '1';
	const target = Number(el.getAttribute('data-count') || 0);
	const duration = 1600;
	const start = performance.now();
	const step = (now) => {
		const elapsed = now - start;
		const progress = Math.min(elapsed / duration, 1);
		const ease = 1 - Math.pow(1 - progress, 3); // ease-out cubic
		el.textContent = Math.floor(ease * target).toLocaleString();
		if (progress < 1) requestAnimationFrame(step);
		else el.textContent = target.toLocaleString();
	};
	requestAnimationFrame(step);
};
const counterObserver = new IntersectionObserver((entries) => {
	entries.forEach((e) => { if (e.isIntersecting) animateCounter(e.target); });
}, { threshold: 0.3 });
counters.forEach((c) => counterObserver.observe(c));

/* ============ SCROLL PROGRESS ============ */
const progressBar = document.getElementById('scroll-progress');
window.addEventListener('scroll', () => {
	const doc = document.documentElement;
	const total = doc.scrollHeight - doc.clientHeight;
	const percent = total > 0 ? (window.scrollY / total) * 100 : 0;
	if (progressBar) progressBar.style.width = `${percent}%`;
}, { passive: true });

/* ============ CURSOR GLOW ============ */
const cursor = document.querySelector('.cursor-glow');
window.addEventListener('mousemove', (event) => {
	if (!cursor) return;
	cursor.style.left = `${event.clientX}px`;
	cursor.style.top = `${event.clientY}px`;
});
document.querySelectorAll('a, button, .gallery-tile, .feature-card, .achievement-card').forEach((el) => {
	el.addEventListener('mouseenter', () => cursor && (cursor.style.width = '40px', cursor.style.height = '40px'));
	el.addEventListener('mouseleave', () => cursor && (cursor.style.width = '18px', cursor.style.height = '18px'));
});

/* ============ PERFORMANCE CHART ============ */
const chartCanvas = document.getElementById('performanceChart');
const initPerformanceChart = () => {
	if (!chartCanvas || chartCanvas.dataset.ready === '1') return;
	chartCanvas.dataset.ready = '1';

	import('chart.js/auto').then(({ default: Chart }) => {
		const ctx = chartCanvas.getContext('2d');
		if (!ctx) return;
		const makeGradient = (h, color1, color2) => {
			const g = ctx.createLinearGradient(0, 0, 0, h);
			g.addColorStop(0, color1);
			g.addColorStop(1, color2);
			return g;
		};

		fetch('/api/performance-chart')
			.then((res) => res.json())
			.then((data) => {
				const h = chartCanvas.height || 360;
				const skyGrad = makeGradient(h, 'rgba(77,183,255,0.95)', 'rgba(77,183,255,0.25)');
				const goldGrad = makeGradient(h, 'rgba(243,207,90,0.95)', 'rgba(212,175,55,0.25)');
				const skyHover = makeGradient(h, 'rgba(120,210,255,1)', 'rgba(77,183,255,0.5)');
				const goldHover = makeGradient(h, 'rgba(255,225,120,1)', 'rgba(243,207,90,0.55)');

				new Chart(chartCanvas, {
					type: 'bar',
					data: {
						labels: data.labels && data.labels.length ? data.labels : [['2024', 'Roxx Champions Cup'], ['2024', 'District League']],
						datasets: [
							{
								label: 'Wins',
								data: data.wins && data.wins.length ? data.wins : [7, 6],
								backgroundColor: skyGrad,
								hoverBackgroundColor: skyHover,
								borderRadius: 14,
								borderSkipped: false,
								barPercentage: 0.55,
								categoryPercentage: 0.65,
							},
							{
								label: 'Points',
								data: data.points && data.points.length ? data.points : [14, 12],
								backgroundColor: goldGrad,
								hoverBackgroundColor: goldHover,
								borderRadius: 14,
								borderSkipped: false,
								barPercentage: 0.55,
								categoryPercentage: 0.65,
							},
						],
					},
					options: {
						responsive: true,
						maintainAspectRatio: false,
						interaction: { mode: 'index', intersect: false },
						animation: {
							duration: 1200,
							easing: 'easeOutQuart',
							delay: (ctx) => ctx.dataIndex * 90 + (ctx.datasetIndex * 140),
						},
						plugins: {
							legend: { display: false },
							tooltip: {
								backgroundColor: 'rgba(8, 16, 30, 0.95)',
								titleColor: '#f3cf5a',
								bodyColor: '#fff',
								borderColor: 'rgba(212,175,55,0.4)',
								borderWidth: 1,
								padding: 12,
								cornerRadius: 10,
								titleFont: { weight: '700', size: 13 },
								bodyFont: { size: 12 },
								displayColors: true,
								boxPadding: 6,
								callbacks: {
									title: (items) => {
										const lbl = items[0]?.label || '';
										return lbl.split(',').join(' · ');
									},
								},
							},
						},
						scales: {
							x: {
								grid: { display: false, drawBorder: false },
								ticks: {
									color: 'rgba(255,255,255,0.75)',
									font: (ctx) => ({
										weight: ctx.tick && ctx.index !== undefined ? '700' : '600',
										size: 12,
									}),
									callback: function (value) {
										return this.getLabelForValue(value);
									},
								},
							},
							y: {
								beginAtZero: true,
								grid: { color: 'rgba(255,255,255,0.06)', drawBorder: false },
								ticks: { color: 'rgba(255,255,255,0.55)', font: { size: 11 } },
							},
						},
					},
				});
			})
			.catch(() => null);
	}).catch(() => null);
};

if (chartCanvas) {
	observeOnce(chartCanvas, initPerformanceChart, { threshold: 0.1 });
}

/* ============ NAV SCROLL EFFECT ============ */
const nav = document.querySelector('.tmc-navbar');
const updateMobileMenuOffset = () => {
	if (!nav) return;
	const navBottom = Math.round(nav.getBoundingClientRect().bottom || 64);
	const offset = Math.max(navBottom + 8, 64);
	document.documentElement.style.setProperty('--tmc-mobile-menu-top', `${offset}px`);
};

const mobileMenuQuery = window.matchMedia('(max-width: 991.98px)');
const clubDropdown = document.querySelector('.tmc-dropdown-hover');
if (clubDropdown) {
	clubDropdown.addEventListener('shown.bs.dropdown', () => {
		if (!mobileMenuQuery.matches) return;
		updateMobileMenuOffset();
	});
}

mobileMenuQuery.addEventListener('change', () => {
	updateMobileMenuOffset();
});

updateMobileMenuOffset();
window.addEventListener('scroll', () => {
	if (!nav) return;
	if (window.scrollY > 30) nav.classList.add('scrolled');
	else nav.classList.remove('scrolled');
	updateMobileMenuOffset();
}, { passive: true });

window.addEventListener('resize', updateMobileMenuOffset, { passive: true });

/* ============ ANIME ACCENT (logo rotation if preloader has chance) ============ */
if (document.querySelector('.tmc-preloader-logo')) {
	import('animejs').then(({ animate }) => {
		animate('.tmc-preloader-logo', { rotate: '1turn', duration: 2200, ease: 'linear', loop: true });
	}).catch(() => null);
}

/* ============ CLUB STORY — expandable narrative ============ */
document.querySelectorAll('[data-story-toggle]').forEach((btn) => {
	btn.addEventListener('click', () => {
		const card = btn.closest('.story-card');
		if (!card) return;
		const isOpen = card.classList.toggle('is-open');
		btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
		const more = btn.querySelector('[data-label-more]');
		const less = btn.querySelector('[data-label-less]');
		if (more && less) {
			more.hidden = isOpen;
			less.hidden = !isOpen;
		}
		const full = card.querySelector('.story-full');
		if (full) full.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
		if (!isOpen) {
			card.scrollIntoView({ behavior: 'smooth', block: 'start' });
		}
	});
});

/* ============ PLAYERS SPOTLIGHT SLIDER ============ */
const playersEl = document.querySelector('.players-swiper');
if (playersEl) {
	observeOnce(playersEl, () => {
		loadSwiperBundle().then(({ Swiper, Autoplay, Pagination, Navigation, EffectCoverflow }) => {
			new Swiper(playersEl, {
				modules: [Autoplay, Pagination, Navigation, EffectCoverflow],
				effect: 'coverflow',
				grabCursor: true,
				centeredSlides: true,
				slidesPerView: 'auto',
				loop: true,
				speed: 700,
				coverflowEffect: {
					rotate: 0,
					stretch: 0,
					depth: 180,
					modifier: 2.2,
					slideShadows: false,
				},
				autoplay: { delay: 3600, disableOnInteraction: false },
				pagination: { el: '.players-pagination', clickable: true },
				navigation: { nextEl: '.spotlight-nav.next', prevEl: '.spotlight-nav.prev' },
				breakpoints: {
					0:    { slidesPerView: 1.1 },
					576:  { slidesPerView: 1.8 },
					768:  { slidesPerView: 2.4 },
					992:  { slidesPerView: 3.2 },
					1200: { slidesPerView: 3.6 },
				},
			});
		}).catch(() => null);
	}, { threshold: 0.05 });
}

/* ============ PERF SECTION VIEWPORT CLASS ============ */
const perfSection = document.getElementById('live-performance');
if (perfSection) {
	const io = new IntersectionObserver((entries) => {
		entries.forEach((entry) => {
			if (entry.isIntersecting) perfSection.classList.add('in-view');
			else perfSection.classList.remove('in-view');
		});
	}, { threshold: 0.12 });
	io.observe(perfSection);
}

/* ============ SECTION REVEAL ON SCROLL ============ */
const revealSections = document.querySelectorAll('[data-section-reveal]');
if (revealSections.length) {
	if ('IntersectionObserver' in window) {
		document.documentElement.classList.add('reveal-init');

		const revealObserver = new IntersectionObserver((entries) => {
			entries.forEach((entry) => {
				if (!entry.isIntersecting) return;
				entry.target.classList.add('is-visible');
				revealObserver.unobserve(entry.target);
			});
		}, {
			threshold: 0.08,
			rootMargin: '0px 0px -4% 0px',
		});

		revealSections.forEach((section) => revealObserver.observe(section));
	} else {
		revealSections.forEach((section) => section.classList.add('is-visible'));
	}
}
