<?php

namespace App\Services;

use App\Models\Follower;
use App\Models\User;
use App\Notifications\NewFollower;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class FollowerService
{
    public function store(int $userId): bool
    {
        $id = Auth::id();
        $follower = Follower::query()
            ->where('user_id', $userId)
            ->where('follower_id', $id);

        if ($follower->exists()) {
            $follower->delete();
            return false;
        }

        Follower::create([
            'user_id' => $userId,
            'follower_id' => $id,
        ]);

        $user = User::findOrFail($userId);
        $follower = User::findOrFail($id);
        Notification::send($user, new NewFollower($follower));
        return true;
    }

}
