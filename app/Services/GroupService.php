<?php

namespace App\Services;

use App\Models\Group;
use App\Models\GroupUser;
use App\RoleEnum;
use App\UserApprovalEnum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class GroupService
{
    public function __construct(public FileUploadService $fileUploadService)
    {

    }

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

    public function updateImage(UploadedFile $image, string $field, string $directory, Group $group)
    {
        $this->fileUploadService->delete($group->$field);
        $path = $this->fileUploadService->update($image, $group->$field, $directory);

        $group->$field = $path;
        $group->save();

        return $path;
    }
}
