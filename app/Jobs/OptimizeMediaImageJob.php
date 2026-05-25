<?php

namespace App\Jobs;

use App\Models\MediaLibrary;
use App\Support\ImageHelper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class OptimizeMediaImageJob implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly int $mediaId)
    {
    }

    public function handle(): void
    {
        $media = MediaLibrary::query()->find($this->mediaId);

        if (! $media || ! $media->image_bytes) {
            return;
        }

        $media->update([
            'thumbnail_bytes' => ImageHelper::generateThumbnail($media->image_bytes),
            'webp_bytes' => ImageHelper::toWebpBytes($media->image_bytes),
            'compressed_size' => strlen($media->image_bytes),
        ]);
    }
}
