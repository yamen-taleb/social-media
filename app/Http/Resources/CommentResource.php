<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'body' => $this->body,
            'time_ago' => $this->getTimeAgo($this->created_at),
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'username' => $this->user->username,
                'avatar' => $this->user->avatar_path ? url('storage/' . $this->user->avatar_path) : null,
            ],
        ];
    }

    /**
     * Get the human-readable time difference
     *
     * @param  \Carbon\Carbon  $date
     * @return string
     */
    protected function getTimeAgo($date)
    {
        if (!$date) {
            return '';
        }

        $now = Carbon::now();

        // If the date is in the future, return 'Just now' as a fallback
        if ($date > $now) {
            return 'Just now';
        }

        $diff = $date->diffForHumans([
            'parts' => 1,
            'short' => true,
        ]);

        return $diff;
    }
}
