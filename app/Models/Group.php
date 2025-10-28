<?php

namespace App\Models;

use App\RoleEnum;
use App\UserApprovalEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Group extends Model
{
    use HasSlug;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'auto_approval',
        'thumbnail_path',
        'cover_path',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function currentUser(): HasOne
    {
        return $this->hasOne(GroupUser::class)
            ->where('user_id', Auth::id());
    }

    public function usersRequests(): BelongsToMany
    {
        return $this->users()
            ->wherePivot('status', UserApprovalEnum::PENDING->value);
    }


    // users who request to join the group

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_users');
    }

    public function members(): BelongsToMany
    {
        return $this->users()
            ->wherePivot('status', UserApprovalEnum::APPROVED->value);
    }

    public function isAdmin(): bool
    {
        return $this->admins()->where('users.id', Auth::id())->exists();
    }

    public function admins(): BelongsToMany
    {
        return $this->users()
            ->wherePivot('role', RoleEnum::ADMIN->value);
    }

    public function isOwner(int $id): bool
    {
        return $this->user_id === $id;
    }

    public function images(): HasManyThrough
    {
        return $this->hasManyThrough(PostAttachment::class, Post::class)
            ->whereIn('post_attachments.type', ['png', 'jpg', 'jpeg'])
            ->latest('post_attachments.created_at');
    }
}
