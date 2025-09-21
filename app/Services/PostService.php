<?php

namespace App\Services;

use App\Models\Post;

class PostService
{
    public function posts()
    {
        return Post::with('user', 'group', 'attachments')
            ->paginate(10);
    }

    public function create(array $post)
    {
        Post::create([
            'title' => $post['title'],
            'description' => $post['description'],
            'user_id' => $post['user_id'],
            'group_id' => $post['group_id'],
        ]);
    }
}
