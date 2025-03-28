<?php

namespace App\Modules\Common\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class FileHandleService
{
    private string $disk;
    private string $basePath;

    public function __construct()
    {
        $this->disk = config('filesystems.default');
        $this->basePath = config('filesystems.base_path', '');
    }

    public function storeFile(UploadedFile $file, string $path): string
    {
        $filePath = $file->store($path, $this->disk);
        
        if (!$filePath) {
            throw new RuntimeException('Failed to store file');
        }

        return $this->getUrl($filePath);
    }

    public function deleteFile(?string $url): bool
    {
        if (!$url) {
            return false;
        }

        $path = $this->getPathFromUrl($url);
        return Storage::disk($this->disk)->delete($path);
    }

    public function getUrl(string $path): string
    {
        $diskConfig = config("filesystems.disks.{$this->disk}");
        
        // If disk has a URL configured, use it
        if (isset($diskConfig['url'])) {
            return rtrim($diskConfig['url'], '/') . '/' . $path;
        }

        // For public disk, use Laravel's URL generation
        if ($this->disk === 'public') {
            return Storage::url($path);
        }

        // For other drivers, return the file path
        return $path;
    }

    private function getPathFromUrl(string $url): string
    {
        $diskConfig = config("filesystems.disks.{$this->disk}");
        
        // If disk has a URL configured, remove it from the path
        if (isset($diskConfig['url'])) {
            $url = str_replace($diskConfig['url'], '', $url);
        }
        
        // For public disk, remove the base path
        if ($this->disk === 'public') {
            $url = str_replace($this->basePath, '', $url);
        }

        // Remove any leading slashes
        return ltrim($url, '/');
    }
} 