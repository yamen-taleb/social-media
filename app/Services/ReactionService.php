<?php

namespace App\Services;

use App\Models\Reaction;
use App\Notifications\PostReaction;
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
        $reactor = Auth::user();
        $modelClass = "App\\Models\\$model";

        $reaction = Reaction::create([
            'user_id' => $reactor->id,
            'model_id' => $id,
            'model_type' => $modelClass,
            'type' => $type,
        ]);

        // Get the reactable model (post or comment)
        $reactable = $modelClass::findOrFail($id);

        if ($reactable->user_id !== $reactor->id) {
            $owner = $reactable->user;
            $owner->notify(new PostReaction($type, $reactable, $reactor));
        }

        return $reaction;
    }

    public function countPostReactions(int $id, string $model): int
    {
        return Reaction::query()
            ->where('model_id', $id)
            ->where('model_type', "App\\Models\\$model")
            ->count();
    }
}
