<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class GroupPageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'thumbnail' => $this->thumbnail_path ? Storage::url($this->thumbnail_path) : 'https://picsum.photos/100',
            'cover' => $this->cover_path ? Storage::url($this->cover_path) : 'https://picsum.photos/100',
            'role' => $this->currentUser?->role,
            'status' => $this->currentUser?->status,
            'auto_approval' => (bool) $this->auto_approval,
        ];
    }
}
