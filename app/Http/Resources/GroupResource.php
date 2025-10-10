<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GroupResource extends JsonResource
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
            'description' => Str::words($this->description, '15'),
            'thumbnail' => $this->thumbnail_path ? Storage::url($this->thumbnail_path) : 'https://picsum.photos/100',
            'role' => $this->pivot->role,
            'status' => $this->pivot->status,
//            'joined_at' => $this->pivot->created_at,
        ];
    }
}
