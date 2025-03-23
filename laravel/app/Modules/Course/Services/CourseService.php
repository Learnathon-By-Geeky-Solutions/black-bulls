<?php

namespace App\Modules\Course\Services;

use App\Modules\Common\Contracts\RepositoryInterface;
use App\Modules\Course\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CourseService
{
    protected $courseRepository;

    public function __construct()
    {
        $this->courseRepository = app()->make(RepositoryInterface::class, ['model'=> new Course()]);
    }

    public function getAll(): LengthAwarePaginator
    {
        return $this->courseRepository->getAll();
    }

    public function getById(int $id): Model
    {
        return $this->courseRepository->getById($id);
    }

    public function create(array $data): Model
    {
        $data['user_id'] = Auth::id();
        return $this->courseRepository->create($data);
    }

    public function update(int $id, array $data): Model
    {
        return $this->courseRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->courseRepository->delete($id);
    }

    public function getUserCourses(): Collection
    {
        $userId = Auth::id();
        return $this->courseRepository->getByIdWithParameters($userId);
    }
}
