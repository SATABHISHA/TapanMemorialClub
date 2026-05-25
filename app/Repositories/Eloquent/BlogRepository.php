<?php

namespace App\Repositories\Eloquent;

use App\Models\Blog;
use App\Repositories\Contracts\BlogRepositoryInterface;

class BlogRepository implements BlogRepositoryInterface
{
    public function latestPublished(int $limit = 6)
    {
        return Blog::query()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->with('user:id,name')
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    public function getAllPaginated(int $perPage = 15)
    {
        return Blog::query()->with('user:id,name')->latest()->paginate($perPage);
    }

    public function create(array $data): Blog
    {
        return Blog::query()->create($data);
    }

    public function update(Blog $blog, array $data): Blog
    {
        $blog->update($data);

        return $blog->refresh();
    }

    public function delete(Blog $blog): void
    {
        $blog->delete();
    }
}
