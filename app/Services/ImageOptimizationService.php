<?php

namespace App\Services;

use App\Models\MediaLibrary;
use App\Support\ImageHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageOptimizationService
{
    public function processAndStore(UploadedFile $file, ?int $userId = null, ?string $module = null, bool $forceBlob = false): MediaLibrary
    {
        $compressed = ImageHelper::compressImage($file);
        $dimensions = ImageHelper::getDimensions($compressed);
        $thumbnailBytes = ImageHelper::generateThumbnail($compressed);
        $webpBytes = ImageHelper::toWebpBytes($compressed);

        $filePath = null;
        $isBlob = $forceBlob || $file->getSize() > (2 * 1024 * 1024);

        if (! $isBlob) {
            $filePath = 'media/'.date('Y/m').'/'.uniqid('tmc_', true).'.'.$file->getClientOriginalExtension();
            Storage::disk('public')->put($filePath, $compressed);
        }

        return MediaLibrary::query()->create([
            'user_id' => $userId,
            'module' => $module,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType() ?? 'image/jpeg',
            'extension' => $file->getClientOriginalExtension(),
            'image_bytes' => $isBlob ? ImageHelper::imageToBytes($compressed) : null,
            'thumbnail_bytes' => $thumbnailBytes,
            'webp_bytes' => $webpBytes,
            'file_path' => $filePath,
            'file_size' => $file->getSize(),
            'compressed_size' => strlen($compressed),
            'width' => $dimensions['width'],
            'height' => $dimensions['height'],
            'is_blob' => $isBlob,
            'hash' => hash('sha256', $compressed),
            'upload_date' => now(),
        ]);
    }
}
