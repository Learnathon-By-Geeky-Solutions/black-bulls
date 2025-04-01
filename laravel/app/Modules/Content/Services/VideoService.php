<?php

namespace App\Modules\Content\Services;

use App\Modules\Common\Contracts\RepositoryInterface;
use App\Modules\Common\Services\FileHandleService;
use App\Modules\Content\Models\Video;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VideoService
{
    protected $videoRepository;
    protected $fileHandleService;
    private const VIDEO_NOT_FOUND = 'Video not found';
    private const VIDEO_PATH = 'videos';
    private const THUMBNAIL_PATH = 'thumbnails';

    public function __construct(FileHandleService $fileHandleService)
    {
        $this->videoRepository = app()->make(RepositoryInterface::class, ['model'=> new Video()]);
        $this->fileHandleService = $fileHandleService;
    }

    public function getAll(): array
    {
        try {
            $videos = $this->videoRepository->getAll();
            
            if ($videos->isEmpty()) {
                return [
                    'is_success' => true,
                    'message' => 'No videos found',
                    'data' => null,
                    'status' => 200
                ];
            }
            
            return [
                'is_success' => true,
                'message' => 'Videos retrieved successfully',
                'data' => $videos,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve videos: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function create(array $data): array
    {
        try {
            DB::beginTransaction();
            
            // Handle video file upload
            if (isset($data['url'])) {
                $data['url'] = $this->fileHandleService->storeFile($data['url'], self::VIDEO_PATH);
            }

            // Handle thumbnail upload
            if (isset($data['thumbnail'])) {
                $data['thumbnail'] = $this->fileHandleService->storeFile($data['thumbnail'], self::THUMBNAIL_PATH);
            }
            
            $video = $this->videoRepository->create($data);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'Video created successfully',
                'data' => $video,
                'status' => 201
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            // Clean up uploaded files if there was an error
            if (isset($data['url'])) {
                $this->fileHandleService->deleteFile($data['url']);
            }
            if (isset($data['thumbnail'])) {
                $this->fileHandleService->deleteFile($data['thumbnail']);
            }
            
            return [
                'is_success' => false,
                'message' => 'Failed to create video: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function getById(int $id): array
    {
        try {
            $video = $this->videoRepository->getById($id);
            
            return [
                'is_success' => true,
                'message' => 'Video retrieved successfully',
                'data' => $video,
                'status' => 200
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'is_success' => false,
                'message' => self::VIDEO_NOT_FOUND,
                'data' => null,
                'status' => 404
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve video: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function update(int $id, array $data): array
    {
        try {
            DB::beginTransaction();
            
            $video = $this->videoRepository->getById($id);
            
            // Handle video file upload
            if (isset($data['url'])) {
                // Delete old video file
                $this->fileHandleService->deleteFile($video->url);
                $data['url'] = $this->fileHandleService->storeFile($data['url'], self::VIDEO_PATH);
            }

            // Handle thumbnail upload
            if (isset($data['thumbnail'])) {
                // Delete old thumbnail
                $this->fileHandleService->deleteFile($video->thumbnail);
                $data['thumbnail'] = $this->fileHandleService->storeFile($data['thumbnail'], self::THUMBNAIL_PATH);
            }
            
            $video = $this->videoRepository->update($id, $data);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'Video updated successfully',
                'data' => $video,
                'status' => 200
            ];
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return [
                'is_success' => false,
                'message' => self::VIDEO_NOT_FOUND,
                'data' => null,
                'status' => 404
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            // Clean up uploaded files if there was an error
            if (isset($data['url'])) {
                $this->fileHandleService->deleteFile($data['url']);
            }
            if (isset($data['thumbnail'])) {
                $this->fileHandleService->deleteFile($data['thumbnail']);
            }
            
            return [
                'is_success' => false,
                'message' => 'Failed to update video: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function delete(int $id): array
    {
        try {
            DB::beginTransaction();
            
            $video = $this->videoRepository->getById($id);
            
            // Delete video file
            $this->fileHandleService->deleteFile($video->url);

            // Delete thumbnail
            $this->fileHandleService->deleteFile($video->thumbnail);
            
            $this->videoRepository->delete($id);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'Video deleted successfully',
                'status' => 200
            ];
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return [
                'is_success' => false,
                'message' => self::VIDEO_NOT_FOUND,
                'status' => 404
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'is_success' => false,
                'message' => 'Failed to delete video: ' . $e->getMessage(),
                'status' => 500
            ];
        }
    }

    public function getByVideoable(string $type, int $id): array
    {
        try {
            $videos = Video::where('videoable_type', $type)
                ->where('videoable_id', $id)
                ->get();
            
            return [
                'is_success' => true,
                'message' => 'Videos retrieved successfully',
                'data' => $videos,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve videos: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }
}
