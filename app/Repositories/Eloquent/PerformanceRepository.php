<?php

namespace App\Repositories\Eloquent;

use App\Models\Performance;
use App\Repositories\Contracts\PerformanceRepositoryInterface;

class PerformanceRepository implements PerformanceRepositoryInterface
{
    public function listForFrontend()
    {
        return Performance::query()->orderByDesc('year')->orderBy('tournament')->get();
    }

    public function getYearOptions()
    {
        return Performance::query()->select('year')->distinct()->orderByDesc('year')->pluck('year');
    }

    public function getAllPaginated(int $perPage = 15)
    {
        return Performance::query()->latest()->paginate($perPage);
    }

    public function create(array $data): Performance
    {
        return Performance::query()->create($data);
    }

    public function update(Performance $performance, array $data): Performance
    {
        $performance->update($data);

        return $performance->refresh();
    }

    public function delete(Performance $performance): void
    {
        $performance->delete();
    }
}
