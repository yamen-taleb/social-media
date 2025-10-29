<?php

namespace App\Services;

use App\Models\Post;
use App\Notifications\CreateGroupPost;
use App\Notifications\DeletePostByAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

class PostService
{
    public function __construct(
        public FileUploadService $fileUploadService,
        public PostAttachmentService $postAttachmentService
    ) {
    }

    public function getGroupPosts($groupId)
    {
        return $this->baseQuery()
            ->where('group_id', $groupId)
            ->orderBy('is_pinned', 'desc')
            ->orderByRaw('CASE WHEN is_pinned = 1 THEN updated_at ELSE created_at END DESC');
    }

    protected function baseQuery()
    {
        return Post::query()
            ->withCommonRelations();
    }

    public function getHomePosts($user, $followingIds, $groupIds)
    {
        return $this->baseQuery()
            ->with('group.currentUser')
            ->where(function($query) use ($user, $followingIds, $groupIds) {
                // User's own posts
                $query->where('posts.user_id', $user->id);

                // Posts from followed users (only non-group posts)
                if ($followingIds->isNotEmpty()) {
                    $query->orWhere(function($q) use ($followingIds) {
                        $q->whereIn('posts.user_id', $followingIds)
                            ->whereNull('posts.group_id');
                    });
                }

                // Posts from user's groups
                if ($groupIds->filter()->isNotEmpty()) {
                    $query->orWhereIn('posts.group_id', $groupIds);
                }
            })
            ->latest('posts.created_at');
    }

    public function create(array $post)
    {
        DB::transaction(function () use ($post) {
            $postRecord = Post::create([
                'title' => $post['title'],
                'description' => $post['description'],
                'user_id' => $post['user_id'],
                'group_id' => $post['group_id'],
            ]);
            $this->postAttachmentService->uploadPostAttachments($post['attachments'], $postRecord->id);

            if ($postRecord->group_id) {
                $postRecord->load('group');
                $group = $postRecord->group;

                $group->load([
                    'users' => function ($query) use ($postRecord) {
                        $query->where('users.id', '!=', $postRecord->user_id);
                    }
                ]);

                Notification::send(
                    $group->users,
                    new CreateGroupPost($postRecord->user, $group->name, $postRecord->id, $group->thumbnail_path)
                );
            }
        });
    }

    public function update(Post $post, array $postData)
    {
        if (isset($postData['deletedAttachmentsIds'])) {
            $this->postAttachmentService->deleteMultiple($postData['deletedAttachmentsIds'], $post->id);
        }
        $this->postAttachmentService->uploadPostAttachments($postData['attachments'], $post->id);
        $post->update([
            'title' => $postData['title'],
            'description' => $postData['description'],
        ]);
    }

    public function delete(Post $post)
    {
        Gate::authorize('delete', $post);

        $group = $post->group;
        $isAdminDeletion = Auth::id() !== $post->user_id;

        if ($isAdminDeletion) {
            $user = $post->user;
            Notification::send($user, new DeletePostByAdmin($user, $group));
        }

        $post->delete();
    }

    public function post(Post $post)
    {
        return $post->load([
            'user', 'group', 'attachments',
            'reactions' => fn($query) => $query->where('user_id', auth()->id())
        ])
            ->loadCount('reactions', 'comments');
    }

    public function search(string $search)
    {
        return $this->baseQuery()
            ->where('title', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->paginate(3);
    }

    public function pin(Post $post)
    {
        if ($post->group_id)
            Gate::authorize('pinOnGroup', $post);
        else
            Gate::authorize('pinOnProfile', $post);

        $post->is_pinned = !$post->is_pinned;
        $post->save();
    }
}
