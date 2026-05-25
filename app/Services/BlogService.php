<?php

namespace App\Services;

use App\Models\Blog;
use App\Repositories\Contracts\BlogRepositoryInterface;
use Illuminate\Support\Str;

class BlogService
{
    public function __construct(private readonly BlogRepositoryInterface $blogRepository)
    {
    }

    public function latest(int $limit = 6)
    {
        return $this->blogRepository->latestPublished($limit);
    }

    public function paginateAdmin(int $perPage = 15)
    {
        return $this->blogRepository->getAllPaginated($perPage);
    }

    public function create(array $data): Blog
    {
        $data['slug'] = Str::slug($data['title']).'-'.Str::lower(Str::random(6));

        return $this->blogRepository->create($data);
    }

    public function update(Blog $blog, array $data): Blog
    {
        if (! empty($data['title'])) {
            $data['slug'] = Str::slug($data['title']).'-'.Str::lower(Str::random(4));
        }

        return $this->blogRepository->update($blog, $data);
    }

    public function delete(Blog $blog): void
    {
        $this->blogRepository->delete($blog);
    }
}
