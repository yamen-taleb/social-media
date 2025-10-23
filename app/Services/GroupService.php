<?php

namespace App\Services;

use App\Http\Requests\addMemberRequest;
use App\Http\Requests\SearchUserGroupRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Mail\GroupInvitationMail;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use App\Notifications\AcceptToJoin;
use App\Notifications\ChangeRole;
use App\Notifications\RequestToJoin;
use App\Notifications\UserRemovedFromGroup;
use App\RoleEnum;
use App\UserApprovalEnum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
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
            'role' => RoleEnum::ADMIN,
            'status' => UserApprovalEnum::APPROVED,
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
            ->whereNotIn('id', $group->members()->pluck('users.id'))
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
            'role' => RoleEnum::MEMBER,
            'status' => UserApprovalEnum::PENDING,
            'token' => Str::random(20),
            'token_expires_at' => now()->addHours(24),
        ]);

        Mail::to($user->email)->queue(new GroupInvitationMail($group, $groupUser));

        return $groupUser;
    }

    public function join(Group $group, User $user)
    {
        $status = UserApprovalEnum::APPROVED;
        if (!$group->auto_approval) {
            $status = UserApprovalEnum::PENDING;
            Notification::send($group->admins, new RequestToJoin($group, $user));
        }

        $this->createGroupUser([
            'user_id' => $user->id,
            'created_by' => $user->id,
            'group_id' => $group->id,
            'role' => RoleEnum::MEMBER,
            'status' => $status,
        ]);
    }

    /**
     * Handle group join request (accept/reject)
     */
    public function handleRequest(Group $group, User $user, string $action)
    {
        Gate::authorize('update', $group);

        $status = $action === 'accept' ? UserApprovalEnum::APPROVED : UserApprovalEnum::REJECTED;

        if ($status === UserApprovalEnum::APPROVED) {
            $user->notify(new AcceptToJoin($group, $user));
        }

        $group->users()->updateExistingPivot($user->id, [
            'status' => $status,
        ]);

        return $status;
    }

    /**
     * Accept group invitation using token
     */
    public function acceptInvitation(Group $group, string $token)
    {
        $groupUser = $group->users()
            ->wherePivot('token', $token)
            ->wherePivot('token_expires_at', '>', now())
            ->firstOrFail();

        $groupUser->pivot->update([
            'status' => UserApprovalEnum::APPROVED,
            'token' => null,
            'token_expires_at' => null,
            'used_at' => now(),
        ]);

        return $groupUser;
    }

    public function updateRole(Group $group, User $user, UpdateRoleRequest $updateRoleRequest)
    {
        $updateRoleRequest = $updateRoleRequest->validated();

        $userInGroup = GroupUser::query()
            ->where('user_id', $user->id)
            ->where('group_id', $group->id)
            ->where('status', UserApprovalEnum::APPROVED)
            ->first();

        if ($group->isOwner($user->id)) {
            return response("You can't change role of the owner of the group", 403);
        }

        if ($userInGroup) {
            $userInGroup->update([
                'role' => $updateRoleRequest['role'],
            ]);
            Notification::send($user, new ChangeRole($group, $updateRoleRequest['role']));
        }
    }

    public function removeMember(Group $group, User $user)
    {
        Gate::authorize('removeMember', [$group, $user->id]);

        $groupUser = GroupUser::query()
            ->where('user_id', $user->id)
            ->where('group_id', $group->id)
            ->delete();

        if ($groupUser) {
            $user->notify(new UserRemovedFromGroup($group));
        }
    }

    public function search($search)
    {
        return Group::query()
            ->whereLike('name', "%$search%")
            ->orWhereLike('description', "%$search%")
            ->take(3)
            ->get();
    }
}
