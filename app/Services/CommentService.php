<?php

namespace App\Services;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    public function comments(int $postId)
    {
        return Comment::query()
            ->where('post_id', $postId)
            ->latest()
            ->paginate(10);
    }

    public function create(string $body, int $postId)
    {
        return Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $postId,
            'body' => nl2br($body),
        ]);
    }
}
