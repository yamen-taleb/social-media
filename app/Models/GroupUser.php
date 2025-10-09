<?php

namespace App\Models;

use App\RoleEnum;
use App\UserApprovalEnum;
use Illuminate\Database\Eloquent\Model;

class GroupUser extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'group_id',
        'created_by',
        'role',
        'status',
    ];

    protected $casts = [
        'status' => UserApprovalEnum::class,
        'role' => RoleEnum::class,
    ];
}
