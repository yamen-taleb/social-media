<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
            ->where('user_id', Auth::id())
            ->withDefault();
    }
}
