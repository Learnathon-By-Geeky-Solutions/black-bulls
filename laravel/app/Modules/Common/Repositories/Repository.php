<?php

namespace App\Modules\Common\Repositories;

use App\Modules\Common\Contracts\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class Repository implements RepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAll(array $columns = ['*'], array $conditions = [], array $relations = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->select($columns);

        foreach ($conditions as $field => $value){
            $query->where($field, $value);
        }

        if (!empty($relations)) {
            $query->with($relations);
        }

        if($perPage && $perPage > 0){
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    public function getById(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function getByIdWithParameters(int $id, array $columns = ['*'], array $conditions = [], array $relations = []): Collection
    {
        $query = $this->model->select($columns)->with($relations)->where('id', $id);

        foreach ($conditions as $field => $value) {
            $query->where($field, $value);
        }
    
        return $this->executeQuery($query, true);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Model
    {
        $model = $this->model->findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function bulkUpdate(array $ids, array $data): int
    {
        $existingIds = $this->model->whereIn('id', $ids)->pluck('id')->toArray();

        if (count($existingIds) !== count($ids)) {
            throw new \Exception("Some records were not found for updating");
        }
    
        return $this->model->whereIn('id', $ids)->update($data);
    }
    

    public function delete(int $id): bool
    {
        return $this->model->destroy($id);
    }

    public function bulkDelete(array $ids): int
    {
        $existingIds = $this->model->whereIn('id', $ids)->pluck('id')->toArray();
        
        if (count($existingIds) !== count($ids)) {
            throw new \Exception("Some records were not found for deletion");
        }
    
        return $this->model->destroy($ids);
    }

    public function executeQuery($query, $single = false)
    {
        if($query instanceof \Illuminate\Database\Eloquent\Builder || $query instanceof \Illuminate\Database\Query\Builder){
            return $single ? $query->first() : $query->get();
        } elseif (is_string($query)){
            $result = DB::select($query);
            return $single ? collect($result)->first() : collect($result);
        } else{
            throw new \InvalidArgumentException('Invalid query');
        }
    }

}
