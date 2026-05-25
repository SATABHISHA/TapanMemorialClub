<?php

namespace App\Services;

use App\Models\Performance;
use App\Repositories\Contracts\PerformanceRepositoryInterface;

class PerformanceService
{
    public function __construct(private readonly PerformanceRepositoryInterface $performanceRepository)
    {
    }

    public function listForFrontend()
    {
        return $this->performanceRepository->listForFrontend();
    }

    public function years()
    {
        return $this->performanceRepository->getYearOptions();
    }

    public function paginateAdmin(int $perPage = 15)
    {
        return $this->performanceRepository->getAllPaginated($perPage);
    }

    public function create(array $data): Performance
    {
        return $this->performanceRepository->create($data);
    }

    public function update(Performance $performance, array $data): Performance
    {
        return $this->performanceRepository->update($performance, $data);
    }

    public function delete(Performance $performance): void
    {
        $this->performanceRepository->delete($performance);
    }
}
