<?php

namespace Database\Seeders;

use App\Models\DynamicPage;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DynamicPageSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->where('email', 'admin@tapanmemorialclub.com')->first();

        $pages = [
            [
                'title' => 'Our Journey',
                'slug' => 'our-journey',
                'description' => 'The story of how Tapan Memorial Club grew from a neighborhood cricket passion into a legacy-driven institution.',
                'content' => <<<TEXT
Founded with the vision of nurturing cricketing talent in Kolkata, Tapan Memorial Club (TMC) became a cornerstone of Bengal cricket.

Over the years, the club has proudly hosted and participated in numerous tournaments, leaving behind a legacy of sportsmanship, discipline, and triumph.

Our journey page is meant to act as the living history section for the club, so the management can update milestones, anniversary notes, and major turning points whenever the story grows.
TEXT,
                'sort_order' => 1,
                'menu_icon' => 'bi-stars',
            ],
            [
                'title' => 'Historic Achievements',
                'slug' => 'historic-achievements',
                'description' => 'Championship runs, knockout victories, and the moments that defined the club on and off the field.',
                'content' => <<<TEXT
CAB League Champions - multiple seasons of dominance in the Cricket Association of Bengal tournaments.

Knockout Victories - memorable wins in inter-club championships, showcasing resilience and teamwork.

Player Development - several TMC players have gone on to represent Bengal and India at higher levels.
TEXT,
                'sort_order' => 2,
                'menu_icon' => 'bi-trophy-fill',
            ],
            [
                'title' => 'Training Academy',
                'slug' => 'training-academy',
                'description' => 'A modern training pathway for juniors, seniors, and players who want to sharpen technique and match temperament.',
                'content' => <<<TEXT
The training academy page can be used for coaching schedules, session structures, fitness programs, and upcoming development camps.

This is ideal for youth intake, net practice plans, and announcements about trials or skill-development clinics.
TEXT,
                'sort_order' => 3,
                'menu_icon' => 'bi-people-fill',
            ],
            [
                'title' => 'Captain’s Corner',
                'slug' => 'captains-corner',
                'description' => 'Leadership notes, match-day thinking, and tactical reflections from the club’s captains and core leaders.',
                'content' => <<<TEXT
Use this page for captain messages, strategy notes, and match reflections.

It is perfect for giving members and supporters a premium, editorial-style club voice without editing the codebase.
TEXT,
                'sort_order' => 4,
                'menu_icon' => 'bi-journal-richtext',
            ],
            [
                'title' => 'Memorable Moments',
                'slug' => 'memorable-moments',
                'description' => 'A visual and written archive of trophy lifts, final overs, and celebrated club memories.',
                'content' => <<<TEXT
From thrilling last-ball finishes to record-breaking performances, this section captures the spirit of TMC’s journey.

Each match is a story of determination, unity, and passion for cricket.
TEXT,
                'sort_order' => 5,
                'menu_icon' => 'bi-camera-reels',
            ],
        ];

        foreach ($pages as $pageData) {
            $page = DynamicPage::query()->updateOrCreate(
                ['slug' => $pageData['slug']],
                [
                    'title' => $pageData['title'],
                    'description' => $pageData['description'],
                    'content' => $pageData['content'],
                    'is_published' => true,
                    'show_on_home' => false,
                    'sort_order' => $pageData['sort_order'],
                    'created_by' => $admin?->id,
                ]
            );

            $menu = Menu::query()->updateOrCreate(
                ['url' => '/pages/'.$page->slug],
                [
                    'title' => $page->title,
                    'slug' => Str::slug($page->title),
                    'type' => 'internal',
                    'icon' => $pageData['menu_icon'],
                    'sort_order' => 50 + $pageData['sort_order'],
                    'banner_media_id' => null,
                    'is_active' => true,
                    'open_in_new_tab' => false,
                    'created_by' => $admin?->id,
                ]
            );

            $page->update(['menu_id' => $menu->id]);
        }
    }
}