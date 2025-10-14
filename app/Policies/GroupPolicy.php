<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use App\RoleEnum;

class GroupPolicy
{
    public function update(User $user, Group $group): bool
    {
        return $group->user_id === $user->id || $group->currentUser->role === RoleEnum::ADMIN;
    }

    public function addNewMember(User $user, Group $group): bool
    {
        return $group->user_id === $user->id || $group->currentUser->role === RoleEnum::ADMIN;
    }
}
