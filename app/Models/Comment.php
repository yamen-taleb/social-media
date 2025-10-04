<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
        'body',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'model');
    }
}
