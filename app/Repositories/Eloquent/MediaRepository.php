<?php

namespace App\Repositories\Eloquent;

use App\Models\MediaLibrary;
use App\Repositories\Contracts\MediaRepositoryInterface;
use App\Services\ImageOptimizationService;
use Illuminate\Http\UploadedFile;

class MediaRepository implements MediaRepositoryInterface
{
    public function __construct(private readonly ImageOptimizationService $imageOptimizationService)
    {
    }

    public function storeImage(UploadedFile $file, ?int $userId = null, ?string $module = null, bool $forceBlob = false): MediaLibrary
    {
        return $this->imageOptimizationService->processAndStore($file, $userId, $module, $forceBlob);
    }

    public function findById(int $id): ?MediaLibrary
    {
        return MediaLibrary::query()->find($id);
    }

    public function paginated(int $perPage = 24)
    {
        return MediaLibrary::query()->latest()->paginate($perPage);
    }

    public function delete(MediaLibrary $media): void
    {
        $media->delete();
    }
}
