<?php

namespace App\Services;

use App\Models\MediaLibrary;
use App\Repositories\Contracts\MediaRepositoryInterface;
use Illuminate\Http\UploadedFile;

class MediaService
{
    public function __construct(private readonly MediaRepositoryInterface $mediaRepository)
    {
    }

    public function upload(UploadedFile $file, ?int $userId = null, ?string $module = null, bool $forceBlob = false): MediaLibrary
    {
        return $this->mediaRepository->storeImage($file, $userId, $module, $forceBlob);
    }

    public function uploadAsBlob(UploadedFile $file, ?int $userId = null, ?string $module = null): MediaLibrary
    {
        return $this->upload($file, $userId, $module, true);
    }

    public function paginated(int $perPage = 24)
    {
        return $this->mediaRepository->paginated($perPage);
    }

    public function find(int $id): ?MediaLibrary
    {
        return $this->mediaRepository->findById($id);
    }

    public function delete(MediaLibrary $media): void
    {
        $this->mediaRepository->delete($media);
    }
}
