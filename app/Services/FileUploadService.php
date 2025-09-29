<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    /**
     * Upload a file to the specified directory
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string $disk
     * @return string
     */
    public function upload(UploadedFile $file, string $directory, string $disk = 'public'): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . uniqid() . '.' . $extension;
        
        return $file->storeAs($directory, $filename, $disk);
    }

    /**
     * Delete a file from storage
     *
     * @param string|null $filePath
     * @param string $disk
     * @return bool
     */
    public function delete(?string $filePath, string $disk = 'public'): bool
    {
        if (empty($filePath)) {
            return false;
        }

        if (Storage::disk($disk)->exists($filePath)) {
            return Storage::disk($disk)->delete($filePath);
        }

        return false;
    }

    /**
     * Update a file - deletes the old one and uploads the new one
     *
     * @param UploadedFile $newFile
     * @param string|null $oldFilePath
     * @param string $directory
     * @param string $disk
     * @return string
     */
    public function update(UploadedFile $newFile, ?string $oldFilePath, string $directory, string $disk = 'public'): string
    {
        $this->delete($oldFilePath, $disk);
        return $this->upload($newFile, $directory, $disk);
    }

    /**
     * Get the URL for a stored file
     *
     * @param string|null $path
     * @param string $disk
     * @return string|null
     */
    public function url(?string $path, string $disk = 'public'): ?string
    {
        if (empty($path)) {
            return null;
        }

        return Storage::disk($disk)->url($path);
    }

    /**
     * Get the full path for a stored file
     *
     * @param string $path
     * @param string $disk
     * @return string
     */
    public function path(string $path, string $disk = 'public'): string
    {
        return Storage::disk($disk)->path($path);
    }

    /**
     * Check if a file exists
     *
     * @param string $path
     * @param string $disk
     * @return bool
     */
    public function exists(string $path, string $disk = 'public'): bool
    {
        return Storage::disk($disk)->exists($path);
    }
}
