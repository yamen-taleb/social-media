<?php

namespace App\Services;

use App\Models\Group;
use App\Models\GroupUser;
use App\RoleEnum;
use App\UserApprovalEnum;
use Illuminate\Support\Facades\Auth;

class GroupService
{
    public function groups()
    {
        return Auth::user()
            ->groups()
            ->select(['groups.id', 'groups.name', 'groups.slug', 'groups.description', 'groups.thumbnail_path'])
            ->orderBy('pivot_created_at', 'desc')
            ->get();
    }

    public function create(array $params)
    {
        $id = Auth::id();
        $group = Group::create([
            'name' => $params['name'],
            'description' => $params['description'] ?? '',
            'auto_approval' => $params['auto_approval'] ?? true,
            'user_id' => $id,
        ]);

        $this->createGroupUser($id, $group->id, $id, RoleEnum::ADMIN);
    }

    public function createGroupUser($userId, $groupId, $createBy, $role, $status = UserApprovalEnum::APPROVED)
    {
        GroupUser::create([
            'user_id' => $userId,
            'created_by' => $createBy,
            'group_id' => $groupId,
            'role' => $role,
            'status' => $status,
        ]);
    }
}
