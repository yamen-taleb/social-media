<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    public function comments(array $filters)
    {
        return Comment::query()
            ->withCount('reactions')
            ->when(isset($filters['post_id']), static function ($query) use ($filters) {
                $query->where('post_id', $filters['post_id'])
                    ->whereNull('parent_id')
                    ->withCount('replies');
            })
            ->when(isset($filters['parent_id']), static function ($query) use ($filters) {
                $query->where('parent_id', $filters['parent_id']);
            })
            ->latest()
            ->paginate(5);
    }

    public function create(array $data)
    {
        return Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $data['post_id'],
            'body' => nl2br($data['body']),
            'parent_id' => $data['parent_id'] ?? null,
        ]);
    }
}
