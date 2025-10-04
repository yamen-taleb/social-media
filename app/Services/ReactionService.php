<?php

namespace App\Services;

use App\Models\Reaction;
use Illuminate\Support\Facades\Auth;

class ReactionService
{
    public function reaction(int $id, string $model): ?Reaction
    {
        return Reaction::query()
            ->where('user_id', Auth::id())
            ->where('model_id', $id)
            ->where('model_type', "App\\Models\\$model")
            ->first();
    }

    public function create(string $type, int $id, string $model)
    {
        return Reaction::create([
            'user_id' => Auth::id(),
            'model_id' => $id,
            'model_type' => "App\\Models\\$model",
            'type' => $type,
        ]);
    }

    public function countPostReactions(int $id, string $model): int
    {
        return Reaction::query()
                    ->where('model_id', $id)
                    ->where('model_type', "App\\Models\\$model")
                    ->count();
    }
}
