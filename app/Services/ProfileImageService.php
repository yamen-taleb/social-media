<?php

namespace App\Services;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfileImageService
{
    public function update(UploadedFile $image, string $field, string $directory)
    {
        $this->destroy($field);

        $path = $this->store($image, $directory);

        auth()->user()->$field  = $path;
        auth()->user()->save();

        return $path;
    }

    public function store(UploadedFile $image, string $directory): string
    {
        $name = time() . '.' . $image->getClientOriginalName();

        $name = $image->storeAs($directory, $name, 'public');

        return $name;
    }

    public function destroy(string $field): void
    {
        $imagePath = auth()->user()->$field;

        if (!empty($imagePath) && Storage::disk('public')->exists($imagePath))
            Storage::disk('public')->delete($imagePath);
    }
}
