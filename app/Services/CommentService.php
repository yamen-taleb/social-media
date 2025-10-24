<?php

namespace App\Services;

use App\Models\Comment;
use App\Notifications\CreatePostComment;
use App\Notifications\DeleteCommentByPostOwner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

class CommentService
{
    public function comments(array $filters)
    {
        $currentUserId = auth()->id();

        return Comment::query()
            ->withCount('reactions')
            ->with([
                'user',
                'reactions' => function($query) use ($currentUserId) {
                    $query->where('user_id', $currentUserId);
                },
            ])
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
        $id = Auth::id();
        $comment = Comment::create([
            'user_id' => $id,
            'post_id' => $data['post_id'],
            'body' => nl2br($data['body']),
            'parent_id' => $data['parent_id'] ?? null,
        ]);

        $postOwner = $comment->post->user;
        if ($postOwner->id !== $id) {
            Notification::send($postOwner, new CreatePostComment($comment->post_id, Auth::user()));
        }

        return $comment;
    }

    public function delete(Comment $comment)
    {
        Gate::authorize('delete', $comment);

        if (!$comment->isOwner()) {
            $user = $comment->user;
            Notification::send($user, new DeleteCommentByPostOwner($user, Auth::user()->name, $comment->post_id));
        }
        $comment->delete();
    }
}
