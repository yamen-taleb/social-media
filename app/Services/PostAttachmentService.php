<?php

namespace App\Services;

use App\Models\PostAttachment;

class PostAttachmentService
{
    public function __construct(public FileUploadService $fileUploadService)
    {}
    public function deleteMultiple(array $ids, int $postId)
    {
        $postAttachments = PostAttachment::where('post_id', $postId)->whereIn('id', $ids)->get();
        foreach ($postAttachments as $postAttachment) {
            $this->delete($postAttachment);
        }
    }

    public function delete(PostAttachment $postAttachment)
    {
       $this->fileUploadService->delete($postAttachment->path);
       $postAttachment->delete();
    }

    public function uploadPostAttachments(array $attachments, int $postId)
    {
        $allPaths = [];
        try {
            foreach ($attachments as $attachment) {
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
