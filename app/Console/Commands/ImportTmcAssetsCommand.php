<?php

namespace App\Console\Commands;

use App\Models\Achievement;
use App\Models\Blog;
use App\Models\GalleryImage;
use App\Models\Performance;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\User;
use App\Services\MediaService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;
use ZipArchive;

class ImportTmcAssetsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tmc:import-assets {--source=C:\\Users\\satab\\Downloads\\TapanMemorialClubDocs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Tapan Memorial Club images and docx content into DB and frontend assets';

    /**
     * Execute the console command.
     */
    public function handle(MediaService $mediaService): int
    {
        $source = (string) $this->option('source');
        if (! File::exists($source) || ! File::isDirectory($source)) {
            $this->error("Source folder not found: {$source}");

            return self::FAILURE;
        }

        $admin = User::query()->where('email', 'admin@tapanmemorialclub.com')->first();
        $userId = $admin?->id;

        $images = collect(File::files($source))
            ->filter(fn ($file) => in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp'], true))
            ->sortBy(fn ($file) => $file->getFilename())
            ->values();

        $docFiles = collect(File::files($source))
            ->filter(fn ($file) => strtolower($file->getExtension()) === 'docx')
            ->values();

        if ($images->isEmpty()) {
            $this->warn('No image files found in source folder.');
        }

        $mediaMap = [];
        foreach ($images as $image) {
            $uploaded = new UploadedFile(
                $image->getPathname(),
                $image->getFilename(),
                File::mimeType($image->getPathname()) ?: 'image/jpeg',
                null,
                true
            );

            $media = $mediaService->upload($uploaded, $userId, 'tmc-import');
            $mediaMap[$image->getFilename()] = $media->id;
            $this->line('Imported image: '.$image->getFilename().' => media#'.$media->id);
        }

        if (isset($mediaMap['Logo.jpeg'])) {
            $logoSource = $source.DIRECTORY_SEPARATOR.'Logo.jpeg';
            File::ensureDirectoryExists(public_path('assets/images'));
            File::copy($logoSource, public_path('assets/images/logo.jpeg'));
            $this->info('Copied Logo.jpeg to public/assets/images/logo.jpeg');
        }

        $firstImagePath = $images->first()?->getPathname();
        if ($firstImagePath) {
            File::ensureDirectoryExists(public_path('assets/images'));
            File::copy($firstImagePath, public_path('assets/images/stadium-bg.jpg'));
        }

        $nonLogoMedia = collect($mediaMap)
            ->reject(fn ($id, $name) => strtolower($name) === 'logo.jpeg')
            ->values();

        foreach ($nonLogoMedia as $index => $mediaId) {
            GalleryImage::query()->updateOrCreate(
                ['media_library_id' => $mediaId],
                [
                    'title' => 'TM Club Gallery '.($index + 1),
                    'category' => $index < 4 ? 'trophy' : 'players',
                    'display_order' => $index + 1,
                    'is_featured' => $index < 8,
                ]
            );
        }

        $sliderMedia = $nonLogoMedia->take(5);
        foreach ($sliderMedia as $index => $mediaId) {
            Slider::query()->updateOrCreate(
                ['media_library_id' => $mediaId],
                [
                    'title' => 'Tapan Memorial Club Highlight '.($index + 1),
                    'subtitle' => 'Elite cricket moments and championship spirit',
                    'description' => 'Dynamic visual from official club archive import.',
                    'sort_order' => $index + 1,
                    'is_active' => true,
                    'ken_burns' => true,
                ]
            );
        }

        $firstAchievementMedia = $nonLogoMedia->first();
        if ($firstAchievementMedia) {
            Achievement::query()->whereNull('media_library_id')->limit(3)->get()->each(
                fn (Achievement $achievement) => $achievement->update(['media_library_id' => $firstAchievementMedia])
            );
        }

        $introText = null;
        $performanceText = null;

        foreach ($docFiles as $docFile) {
            $filename = strtolower($docFile->getFilename());
            $text = $this->extractDocxText($docFile->getPathname());

            if (str_contains($filename, 'introduction')) {
                $introText = $text;
            }

            if (str_contains($filename, 'performance')) {
                $performanceText = $text;
            }
        }

        if ($introText) {
            Setting::query()->updateOrCreate(
                ['key' => 'club_intro_text'],
                ['group' => 'club', 'value' => $introText, 'type' => 'textarea', 'is_public' => true]
            );

            Blog::query()->updateOrCreate(
                ['slug' => 'tmc-introduction-imported'],
                [
                    'user_id' => $userId,
                    'title' => 'TM Club Introduction',
                    'excerpt' => mb_substr($introText, 0, 180),
                    'content' => $introText,
                    'status' => 'published',
                    'published_at' => now(),
                ]
            );
        }

        if ($performanceText) {
            Setting::query()->updateOrCreate(
                ['key' => 'club_performance_text'],
                ['group' => 'club', 'value' => $performanceText, 'type' => 'textarea', 'is_public' => true]
            );

            Performance::query()->latest('year')->first()?->update([
                'description' => mb_substr($performanceText, 0, 1800),
            ]);
        }

        $this->info('Asset import completed.');

        return self::SUCCESS;
    }

    private function extractDocxText(string $path): string
    {
        $zip = new ZipArchive();
        $openResult = $zip->open($path);

        if ($openResult !== true) {
            return '';
        }

        $xml = $zip->getFromName('word/document.xml') ?: '';
        $zip->close();

        if ($xml === '') {
            return '';
        }

        $xml = str_replace(['</w:p>', '</w:tr>', '</w:tc>', '<w:br/>', '<w:br />'], "\n", $xml);
        $text = strip_tags($xml);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $text = preg_replace("/\n{2,}/", "\n", $text) ?: $text;

        return trim($text);
    }
}
