<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'title',
        'description',
        'is_approved',
        'deleted_by',
        'deleted_at',
        'user_id',
        'created_at',
        'updated_at',
    ];

    public function attachments(): HasMany
    {
        return $this->hasMany(PostAttachment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'model');
    }

    public function comments(): HasMany
    {
       return $this->hasMany(Comment::class);
    }
}
