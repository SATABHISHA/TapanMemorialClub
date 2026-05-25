<?php

namespace App\Repositories\Contracts;

use App\Models\Blog;

interface BlogRepositoryInterface
{
    public function latestPublished(int $limit = 6);

    public function getAllPaginated(int $perPage = 15);

    public function create(array $data): Blog;

    public function update(Blog $blog, array $data): Blog;

    public function delete(Blog $blog): void;
}
