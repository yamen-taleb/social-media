<?php

namespace App\Services;

use App\Models\Reaction;
use Illuminate\Support\Facades\Auth;

class ReactionService
{
    public function reaction(int $postId): ?Reaction
    {
        return Reaction::query()
            ->where('user_id', Auth::id())
            ->where('post_id', $postId)
            ->first();
    }

    public function create(string $type, int $postId)
    {
        return Reaction::create([
            'user_id' => Auth::id(),
            'post_id' => $postId,
            'type' => $type,
        ]);
    }

    public function countPostReactions(int $postId): int
    {
        return Reaction::query()
                    ->where('post_id', $postId)
                    ->count();
    }
}
