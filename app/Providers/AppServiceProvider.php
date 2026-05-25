<?php

namespace App\Providers;

use App\Repositories\Contracts\BlogRepositoryInterface;
use App\Repositories\Contracts\MediaRepositoryInterface;
use App\Repositories\Contracts\MenuRepositoryInterface;
use App\Repositories\Contracts\PerformanceRepositoryInterface;
use App\Repositories\Eloquent\BlogRepository;
use App\Repositories\Eloquent\MediaRepository;
use App\Repositories\Eloquent\MenuRepository;
use App\Repositories\Eloquent\PerformanceRepository;
use App\Services\MenuService;
use App\Models\Setting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MenuRepositoryInterface::class, MenuRepository::class);
        $this->app->bind(PerformanceRepositoryInterface::class, PerformanceRepository::class);
        $this->app->bind(BlogRepositoryInterface::class, BlogRepository::class);
        $this->app->bind(MediaRepositoryInterface::class, MediaRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->applyDynamicMailConfig();

        View::composer('*', function ($view): void {
            if (app()->runningInConsole()) {
                return;
            }

            static $shared = null;

            if ($shared === null) {
                $shared = [
                    'menus' => collect(),
                    'siteConfig' => $this->defaultSiteConfig(),
                ];

                try {
                    $shared['menus'] = app(MenuService::class)->getFrontendMenus();
                } catch (Throwable) {
                    $shared['menus'] = collect();
                }

                try {
                    if (Schema::hasTable('settings')) {
                        $rows = Setting::query()->where('is_public', true)->pluck('value', 'key');
                        $shared['siteConfig'] = array_replace($shared['siteConfig'], [
                            'contact_address' => (string) ($rows->get('contact_address') ?: $shared['siteConfig']['contact_address']),
                            'contact_phone' => (string) ($rows->get('contact_phone') ?: $shared['siteConfig']['contact_phone']),
                            'contact_email' => (string) ($rows->get('contact_email') ?: $shared['siteConfig']['contact_email']),
                            'contact_latitude' => (string) ($rows->get('contact_latitude') ?: ''),
                            'contact_longitude' => (string) ($rows->get('contact_longitude') ?: ''),
                            'contact_map_embed_url' => (string) ($rows->get('contact_map_embed_url') ?: $shared['siteConfig']['contact_map_embed_url']),
                            'contact_whatsapp_number' => (string) ($rows->get('contact_whatsapp_number') ?: $shared['siteConfig']['contact_whatsapp_number']),
                            'social_instagram_url' => (string) ($rows->get('social_instagram_url') ?: ''),
                            'social_facebook_url' => (string) ($rows->get('social_facebook_url') ?: ''),
                            'social_youtube_url' => (string) ($rows->get('social_youtube_url') ?: ''),
                            'social_twitter_url' => (string) ($rows->get('social_twitter_url') ?: ''),
                            'social_linkedin_url' => (string) ($rows->get('social_linkedin_url') ?: ''),
                        ]);
                    }
                } catch (Throwable) {
                    $shared['siteConfig'] = $this->defaultSiteConfig();
                }
            }

            try {
                $view->with('globalMenus', $shared['menus']);
                $view->with('siteConfig', $shared['siteConfig']);
            } catch (Throwable) {
                $view->with('globalMenus', collect());
                $view->with('siteConfig', $this->defaultSiteConfig());
            }
        });
    }

    private function defaultSiteConfig(): array
    {
        return [
            'contact_address' => '',
            'contact_phone' => '',
            'contact_email' => '',
            'contact_latitude' => '',
            'contact_longitude' => '',
            'contact_map_embed_url' => 'https://www.google.com/maps?q=Kolkata&output=embed',
            'contact_whatsapp_number' => '',
            'social_instagram_url' => '',
            'social_facebook_url' => '',
            'social_youtube_url' => '',
            'social_twitter_url' => '',
            'social_linkedin_url' => '',
        ];
    }

    private function applyDynamicMailConfig(): void
    {
        if ($this->app->runningInConsole() && ! $this->app->runningUnitTests()) {
            // still apply for queued mailables in console workers
        }

        try {
            if (! Schema::hasTable('settings')) {
                return;
            }

            $rows = Setting::query()->where('group', 'mail')->pluck('value', 'key');
            if ($rows->isEmpty()) {
                return;
            }

            $host = trim((string) $rows->get('mail_host'));
            if ($host === '') {
                return; // no SMTP yet — keep default mailer
            }

            Config::set('mail.default', $rows->get('mail_mailer') ?: 'smtp');
            Config::set('mail.mailers.smtp.host', $host);
            Config::set('mail.mailers.smtp.port', (int) ($rows->get('mail_port') ?: 587));
            Config::set('mail.mailers.smtp.username', $rows->get('mail_username') ?: null);
            Config::set('mail.mailers.smtp.password', $rows->get('mail_password') ?: null);
            $enc = $rows->get('mail_encryption');
            Config::set('mail.mailers.smtp.encryption', $enc !== '' ? $enc : null);

            if ($from = $rows->get('mail_from_address')) {
                Config::set('mail.from.address', $from);
            }
            if ($fromName = $rows->get('mail_from_name')) {
                Config::set('mail.from.name', $fromName);
            }
        } catch (Throwable) {
            // silently ignore — never block app boot
        }
    }
}
