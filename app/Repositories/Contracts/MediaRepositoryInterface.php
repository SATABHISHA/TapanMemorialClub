<?php

namespace App\Repositories\Contracts;

use App\Models\MediaLibrary;
use Illuminate\Http\UploadedFile;

interface MediaRepositoryInterface
{
    public function storeImage(UploadedFile $file, ?int $userId = null, ?string $module = null, bool $forceBlob = false): MediaLibrary;

    public function findById(int $id): ?MediaLibrary;

    public function paginated(int $perPage = 24);

    public function delete(MediaLibrary $media): void;
}
