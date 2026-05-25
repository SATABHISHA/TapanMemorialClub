<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Submenu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        Submenu::query()->withTrashed()->forceDelete();
        Menu::query()->withTrashed()->forceDelete();

        $menus = [
            ['title' => 'Home', 'url' => '/', 'icon' => 'bi-house-heart', 'submenus' => []],
            ['title' => 'About', 'url' => '#introduction', 'icon' => 'bi-info-circle', 'submenus' => [
                ['title' => 'Our Story', 'url' => '#introduction'],
                ['title' => 'Heritage Foundation', 'url' => '#introduction'],
                ['title' => 'Leadership', 'url' => '#introduction'],
                ['title' => 'Vision 2030', 'url' => '#introduction'],
            ]],
            ['title' => 'Achievements', 'url' => '#achievements', 'icon' => 'bi-trophy', 'submenus' => [
                ['title' => 'Trophy Cabinet', 'url' => '#achievements'],
                ['title' => 'Hall of Fame', 'url' => '#achievements'],
                ['title' => 'Records Wall', 'url' => '#achievements'],
            ]],
            ['title' => 'Players', 'url' => '#gallery', 'icon' => 'bi-people', 'submenus' => [
                ['title' => 'Senior Squad', 'url' => '#gallery'],
                ['title' => 'U-19 Pipeline', 'url' => '#gallery'],
                ['title' => 'Coaching Staff', 'url' => '#gallery'],
                ['title' => 'Alumni Network', 'url' => '#gallery'],
            ]],
            ['title' => 'Performance', 'url' => '#live-performance', 'icon' => 'bi-graph-up-arrow', 'submenus' => [
                ['title' => 'Season Analytics', 'url' => '#live-performance'],
                ['title' => 'Match Highlights', 'url' => '#live-performance'],
                ['title' => 'Stat Leaders', 'url' => '#live-performance'],
            ]],
            ['title' => 'Gallery', 'url' => '#gallery', 'icon' => 'bi-images', 'submenus' => [
                ['title' => 'Match Day', 'url' => '#gallery'],
                ['title' => 'Training Camps', 'url' => '#gallery'],
                ['title' => 'Trophy Moments', 'url' => '#gallery'],
            ]],
            ['title' => 'News', 'url' => '#news', 'icon' => 'bi-newspaper', 'submenus' => [
                ['title' => 'Latest Updates', 'url' => '#news'],
                ['title' => 'Press Releases', 'url' => '#news'],
                ['title' => 'Vlogs', 'url' => '#news'],
            ]],
            ['title' => 'Contact', 'url' => '#contact', 'icon' => 'bi-envelope-paper', 'submenus' => []],
        ];

        foreach ($menus as $i => $data) {
            $menu = Menu::query()->create([
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'type' => 'internal',
                'url' => $data['url'],
                'icon' => $data['icon'] ?? null,
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);

            foreach ($data['submenus'] as $j => $sub) {
                Submenu::query()->create([
                    'menu_id' => $menu->id,
                    'title' => $sub['title'],
                    'slug' => Str::slug($sub['title']),
                    'url' => $sub['url'],
                    'sort_order' => $j + 1,
                    'is_active' => true,
                ]);
            }
        }
    }
}
