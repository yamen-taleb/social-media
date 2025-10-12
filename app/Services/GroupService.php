<?php

namespace App\Services;

use App\Http\Requests\addMemberRequest;
use App\Http\Requests\SearchUserGroupRequest;
use App\Mail\GroupInvitationMail;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use App\RoleEnum;
use App\UserApprovalEnum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

        $this->createGroupUser([
            'user_id' => $id,
            'created_by' => $id,
            'group_id' => $group->id,
            'role' => RoleEnum::ADMIN->value,
            'status' => UserApprovalEnum::APPROVED->value,
        ]);
    }

    public function createGroupUser(array $records)
    {
        return GroupUser::create($records);
    }

    public function updateImage(UploadedFile $image, string $field, string $directory, Group $group)
    {
        $this->fileUploadService->delete($group->$field);
        $path = $this->fileUploadService->update($image, $group->$field, $directory);

        $group->$field = $path;
        $group->save();

        return $path;
    }

    public function searchUsers(Group $group, SearchUserGroupRequest $request)
    {
        $users = User::where(function ($query) use ($request) {
            $query->where('name', 'like', "%{$request->search}%")
                ->orWhere('username', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%");
        })
            ->whereNotIn('id', $group->members()->where('status', UserApprovalEnum::APPROVED->value)->pluck('users.id'))
            ->select('id', 'name', 'username', 'avatar_path')
            ->limit(10)
            ->get();

        return $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'avatar' => $user->avatar_path ? url('storage/'.$user->avatar_path) : null,
            ];
        });
    }

    public function addMember(Group $group, addMemberRequest $request)
    {
        $user = $request->user;

        $groupUser = $request->groupUser;

        if ($groupUser?->exists()) {
            $groupUser->delete();
        }

        $groupUser = $this->createGroupUser([
            'user_id' => $request->user_id,
            'created_by' => Auth::id(),
            'group_id' => $group->id,
            'role' => RoleEnum::MEMBER->value,
            'status' => UserApprovalEnum::PENDING->value,
            'token' => Str::random(20),
            'token_expires_at' => now()->addHours(24),
        ]);

        Mail::to($user->email)->queue(new GroupInvitationMail($group, $groupUser));

        return $groupUser;
    }
}
