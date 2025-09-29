<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfileImageService
{
    public function __construct(public FileUploadService $fileUploadService)
    {
    }

    public function update(UploadedFile $image, string $field, string $directory)
    {
        $this->fileUploadService->delete(auth()->user()->$field);
        $path = $this->fileUploadService->update($image, auth()->user()->$field, $directory);

        auth()->user()->$field  = $path;
        auth()->user()->save();

        return $path;
    }
}
