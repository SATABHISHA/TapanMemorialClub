<?php

namespace App\Repositories\Eloquent;

use App\Models\Menu;
use App\Repositories\Contracts\MenuRepositoryInterface;
use Illuminate\Support\Collection;

class MenuRepository implements MenuRepositoryInterface
{
    public function getActiveWithSubmenus(): Collection
    {
        return Menu::query()
            ->where('is_active', true)
            ->with(['submenus' => function ($query): void {
                $query->where('is_active', true)->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();
    }

    public function getAllPaginated(int $perPage = 15)
    {
        return Menu::query()->latest()->paginate($perPage);
    }

    public function create(array $data): Menu
    {
        return Menu::query()->create($data);
    }

    public function update(Menu $menu, array $data): Menu
    {
        $menu->update($data);

        return $menu->refresh();
    }

    public function delete(Menu $menu): void
    {
        $menu->delete();
    }

    public function reorder(array $ids): void
    {
        foreach ($ids as $index => $id) {
            Menu::query()->whereKey($id)->update(['sort_order' => $index + 1]);
        }
    }
}
