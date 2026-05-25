<?php

namespace App\Services;

use App\Models\Menu;
use App\Repositories\Contracts\MenuRepositoryInterface;
use Illuminate\Support\Str;

class MenuService
{
    public function __construct(private readonly MenuRepositoryInterface $menuRepository)
    {
    }

    public function getFrontendMenus()
    {
        return $this->menuRepository->getActiveWithSubmenus();
    }

    public function paginateAdmin(int $perPage = 15)
    {
        return $this->menuRepository->getAllPaginated($perPage);
    }

    public function create(array $data): Menu
    {
        $data['slug'] = Str::slug($data['title']);

        return $this->menuRepository->create($data);
    }

    public function update(Menu $menu, array $data): Menu
    {
        if (! empty($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        return $this->menuRepository->update($menu, $data);
    }

    public function reorder(array $ids): void
    {
        $this->menuRepository->reorder($ids);
    }

    public function delete(Menu $menu): void
    {
        $this->menuRepository->delete($menu);
    }
}
