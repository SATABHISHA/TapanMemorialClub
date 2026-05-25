<?php

namespace App\Repositories\Contracts;

use App\Models\Menu;
use Illuminate\Support\Collection;

interface MenuRepositoryInterface
{
    public function getActiveWithSubmenus(): Collection;

    public function getAllPaginated(int $perPage = 15);

    public function create(array $data): Menu;

    public function update(Menu $menu, array $data): Menu;

    public function delete(Menu $menu): void;

    public function reorder(array $ids): void;
}
