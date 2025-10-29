<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationsResource extends JsonResource
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
            'message' => $this->data['message'],
            'avatar' => $this->data['avatar'],
            'url' => $this->data['url'],
            'action' => $this->data['action'],
            'type' => $this->type,
            'read' => $this->read_at,
            'time' => $this->created_at->diffForHumans(),
        ];
    }
}
