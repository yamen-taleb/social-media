<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostAttachment;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

class PostService
{
    public function __construct(public FileUploadService $fileUploadService)
    {}
    public function posts()
    {
        return Post::with('user', 'group', 'attachments')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
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
            $this->uploadPostAttachments($post, $postRecord->id);
        });
    }

    public function update(Post $post, array $postData)
    {
       $post->update([
           'title' => $postData['title'],
           'description' => $postData['description'],
       ]);
    }

    /**
     * @throws \Exception
     */
    public function uploadPostAttachments(array $post, int $postId)
    {
        $allPaths = [];
        try {
            foreach ($post['attachments'] as $attachment) {
                $path = $this->fileUploadService->upload($attachment, 'posts-attachments');
                $allPaths[] = $path;
                PostAttachment::create([
                    'post_id' => $postId,
                    'path' => $path,
                    'type' => $attachment->getClientOriginalExtension(),
                ]);
            }
        } catch (\Exception $e) {
            foreach ($allPaths as $path) {
                $this->fileUploadService->delete($path);
            }
            throw $e;
        }
    }
}
