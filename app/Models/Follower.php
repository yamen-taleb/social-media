<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'follower_id',
    ];

    public function isFollowing($userId)
    {
        return $this->where('user_id', $userId)->where('follower_id', auth()->id())->exists();
    }
}
