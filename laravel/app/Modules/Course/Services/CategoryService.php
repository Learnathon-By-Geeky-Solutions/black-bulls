<?php

namespace App\Modules\Course\Services;

use App\Modules\Common\Contracts\RepositoryInterface;
use App\Modules\Common\Services\FileHandleService;
use App\Modules\Course\Models\Category;
use Illuminate\Support\Facades\DB;
use Exception;

class CategoryService
{
    protected $categoryRepository;
    protected $fileHandleService;
    private const THUMBNAIL_PATH = 'thumbnails/categories';

    public function __construct(FileHandleService $fileHandleService)
    {
        $this->categoryRepository = app()->make(RepositoryInterface::class, ['model' => new Category()]);
        $this->fileHandleService = $fileHandleService;
    }

    public function getAll(): array
    {
        $categories = $this->categoryRepository->getAll();
        
        return [
            'is_success' => true,
            'message' => 'Categories retrieved successfully',
            'data' => $categories,
            'status' => 200
        ];
    }

    public function getById(int $id): array
    {
        $category = $this->categoryRepository->getById($id);
        
        return [
            'is_success' => true,
            'message' => 'Category retrieved successfully',
            'data' => $category,
            'status' => 200
        ];
    }

    public function create(array $data): array
    {
        try {
            DB::beginTransaction();
            
            // Handle image upload
            if (isset($data['image'])) {
                $data['image'] = $this->fileHandleService->storeFile($data['image'], self::THUMBNAIL_PATH);
            }

            $category = $this->categoryRepository->create($data);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'Category created successfully',
                'data' => $category,
                'status' => 201
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            // Clean up uploaded image if there was an error
            if (isset($data['image'])) {
                $this->fileHandleService->deleteFile($data['image']);
            }
            
            return [
                'is_success' => false,
                'message' => 'Failed to create category: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function update(int $id, array $data): array
    {
        try {
            DB::beginTransaction();
            
            $category = $this->categoryRepository->getById($id);
            
            // Handle image upload
            if (isset($data['image'])) {
                // Delete old image
                $this->fileHandleService->deleteFile($category->image);
                $data['image'] = $this->fileHandleService->storeFile($data['image'], self::THUMBNAIL_PATH);
            }
            
            $category = $this->categoryRepository->update($id, $data);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'Category updated successfully',
                'data' => $category,
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            // Clean up uploaded image if there was an error
            if (isset($data['image'])) {
                $this->fileHandleService->deleteFile($data['image']);
            }
            
            return [
                'is_success' => false,
                'message' => 'Failed to update category: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function delete(int $id): array
    {
        try {
            DB::beginTransaction();
            
            $category = $this->categoryRepository->getById($id);
            
            // Delete image
            $this->fileHandleService->deleteFile($category->image);
            
            $this->categoryRepository->delete($id);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'Category deleted successfully',
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'is_success' => false,
                'message' => 'Failed to delete category: ' . $e->getMessage(),
                'status' => 500
            ];
        }
    }
}
