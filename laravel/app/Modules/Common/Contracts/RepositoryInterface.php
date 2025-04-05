<?php

namespace App\Modules\Common\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

interface RepositoryInterface
{
    public function getAll(array $columns = ['*'], array $conditions = [], array $relations = [], int $perPage = 15): LengthAwarePaginator;
    public function getAllWithParameters(array $columns = ['*'], array $conditions = [], array $relations = [], array $orderBy = []): Collection;
    public function getById(int $id): Model;
    public function getByIdWithParameters(int $id, array $columns = ['*'], array $conditions = [], array $relations = []): Collection;
    public function create(array $data): Model;
    public function update(int $id, array $data): Model;
    public function bulkUpdate(array $ids, array $data): int;
    public function delete(int $id): bool;
    public function bulkDelete(array $ids): int;
    public function executeQuery($query, $single = false);
}
