<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\DB;

class PostService
{
    public function __construct(
        public FileUploadService $fileUploadService,
        public PostAttachmentService $postAttachmentService
    ) {
    }

    public function posts()
    {
        return Post::with([
            'user', 'group', 'attachments',
            'reactions' => fn($query) => $query->where('user_id', auth()->id())
        ])
            ->withCount('reactions', 'comments')
            ->orderBy('created_at', 'desc');
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
}
