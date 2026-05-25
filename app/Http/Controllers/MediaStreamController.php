<?php

namespace App\Http\Controllers;

use App\Services\MediaService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class MediaStreamController extends Controller
{
    public function __construct(private readonly MediaService $mediaService)
    {
    }

    public function show(int $id): Response
    {
        $media = $this->mediaService->find($id);

        if (! $media) {
            abort(404);
        }

        if (! $media->is_blob && $media->file_path && Storage::disk('public')->exists($media->file_path)) {
            return response(Storage::disk('public')->get($media->file_path), 200)
                ->header('Content-Type', $media->mime_type)
                ->header('Cache-Control', 'public, max-age=604800');
        }

        if (! $media->image_bytes) {
            abort(404);
        }

        return response($media->image_bytes, 200)
            ->header('Content-Type', $media->mime_type)
            ->header('Cache-Control', 'public, max-age=604800');
    }

    public function thumb(int $id): Response
    {
        $media = $this->mediaService->find($id);

        if (! $media || ! $media->thumbnail_bytes) {
            abort(404);
        }

        return response($media->thumbnail_bytes, 200)
            ->header('Content-Type', 'image/jpeg')
            ->header('Cache-Control', 'public, max-age=604800');
    }
}
