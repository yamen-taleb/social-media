<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;

class GroupPolicy
{
    public function update(User $user, Group $group): bool
    {
        return $group->user_id === $user->id;
    }

    public function addNewMember(User $user, Group $group): bool
    {
        return $group->user_id === $user->id;
    }
}
