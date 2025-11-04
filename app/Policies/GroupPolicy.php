<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\Group;
use App\Models\User;

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

    public function removeMember(User $user, Group $group, int $userId): bool
    {
        return $group->isAdmin() && !$group->isOwner($userId);
    }
}
