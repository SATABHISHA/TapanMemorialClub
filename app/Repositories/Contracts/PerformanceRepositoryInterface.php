<?php

namespace App\Repositories\Contracts;

use App\Models\Performance;

interface PerformanceRepositoryInterface
{
    public function listForFrontend();

    public function getYearOptions();

    public function getAllPaginated(int $perPage = 15);

    public function create(array $data): Performance;

    public function update(Performance $performance, array $data): Performance;

    public function delete(Performance $performance): void;
}
