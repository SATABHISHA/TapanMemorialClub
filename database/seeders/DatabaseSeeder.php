<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Blog;
use App\Models\GalleryImage;
use App\Models\Menu;
use App\Models\Performance;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\Sponsor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::findOrCreate('admin');
        Role::findOrCreate('member');

        $admin = User::query()->firstOrCreate([
            'email' => 'admin@tapanmemorialclub.com',
        ], [
            'name' => 'TMC Super Admin',
            'phone' => '+91-9000000000',
            'password' => 'password',
        ]);

        $admin->assignRole('admin');

        $this->call(DynamicPageSeeder::class);

        Menu::query()->insert([
            ['title' => 'Home', 'slug' => 'home', 'type' => 'internal', 'url' => '/', 'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Achievements', 'slug' => 'achievements', 'type' => 'internal', 'url' => '#achievements', 'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Gallery', 'slug' => 'gallery', 'type' => 'internal', 'url' => '#gallery', 'sort_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'News', 'slug' => 'news', 'type' => 'internal', 'url' => '#news', 'sort_order' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Contact', 'slug' => 'contact', 'type' => 'internal', 'url' => '#contact', 'sort_order' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        Slider::query()->create([
            'title' => 'Champions Mindset',
            'subtitle' => 'Precision, passion, and proud maroon-blue spirit.',
            'description' => 'Add slider images from admin media library and connect IDs to this section.',
            'sort_order' => 1,
            'is_active' => true,
            'ken_burns' => true,
        ]);

        Performance::query()->insert([
            ['year' => 2020, 'tournament' => 'Roxx Champions Cup', 'position' => 'Champion', 'matches_played' => 8, 'wins' => 7, 'losses' => 1, 'points' => 14, 'description' => 'Clinical all-round performance through knockout stages.', 'is_featured' => true, 'created_at' => now(), 'updated_at' => now()],
            ['year' => 2024, 'tournament' => 'District League Invitational', 'position' => 'Runner-up', 'matches_played' => 9, 'wins' => 6, 'losses' => 3, 'points' => 12, 'description' => 'Strong campaign with standout bowling partnerships.', 'is_featured' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        Achievement::query()->insert([
            ['title' => 'Franchise Cup Winner', 'description' => 'Lifted elite local title with dominant final.', 'year' => 2020, 'is_featured' => true, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'League Runner-up', 'description' => 'High-intensity season with tactical consistency.', 'year' => 2024, 'is_featured' => true, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        GalleryImage::query()->insert([
            ['title' => 'Captain with Trophy', 'category' => 'trophy', 'display_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Team Celebration', 'category' => 'team', 'display_order' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        Blog::query()->insert([
            [
                'user_id' => $admin->id,
                'title' => 'A New Era of TMC Cricket',
                'slug' => 'a-new-era-of-tmc-cricket',
                'excerpt' => 'How Tapan Memorial Club blends legacy with modern game intelligence.',
                'content' => 'The club is scaling training intensity, analytics-led match planning, and youth pathways to compete at higher stages.',
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Sponsor::query()->insert([
            ['name' => 'Roxx', 'tier' => 'Title Partner', 'is_active' => true, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'League Energy', 'tier' => 'Associate', 'is_active' => true, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        Setting::query()->insert([
            ['group' => 'club', 'key' => 'club_name', 'value' => 'Tapan Memorial Club', 'type' => 'text', 'is_public' => true, 'created_at' => now(), 'updated_at' => now()],
            ['group' => 'club', 'key' => 'club_estd', 'value' => '1942', 'type' => 'text', 'is_public' => true, 'created_at' => now(), 'updated_at' => now()],
            ['group' => 'social', 'key' => 'instagram', 'value' => 'https://instagram.com/', 'type' => 'url', 'is_public' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
