<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'model_id',
        'model_type',
        'type',
    ];

    public function reactable()
    {
        return $this->morphTo();
    }
}
