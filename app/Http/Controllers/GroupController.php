<?php

namespace App\Http\Controllers;

use App\Http\Requests\addMemberRequest;
use App\Http\Requests\SearchUserGroupRequest;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Requests\ValidateImageRequest;
use App\Http\Resources\GroupPageResource;
use App\Models\Group;
use App\Models\GroupUser;
use App\Services\GroupService;
use App\UserApprovalEnum;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class GroupController extends Controller
{
    public function __construct(public GroupService $groupService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGroupRequest $request)
    {
        $data = $request->validated();

        $this->groupService->create($data);

        return back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        $group->load('currentUser');

        return Inertia::render('Groups/View', [
            'group' => new GroupPageResource($group)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        //
    }

    public function updateCover(ValidateImageRequest $request, Group $group)
    {
        Gate::authorize('update', $group);

        $this->groupService->updateImage($request->file('image'), 'cover_path', 'covers', $group);
    }

    public function updateAvatar(ValidateImageRequest $request, Group $group)
    {
        Gate::authorize('update', $group);

        $this->groupService->updateImage($request->file('image'), 'thumbnail_path', 'avatars', $group);
    }

    public function searchUsers(Group $group, SearchUserGroupRequest $request)
    {
        $members = $this->groupService->searchUsers($group, $request);

        return response()->json($members);
    }

    public function addMember(Group $group, addMemberRequest $request)
    {
        $this->groupService->addMember($group, $request);

        return response()->json([
            'message' => 'Member added successfully'
        ]);
    }

    public function acceptInvitation(Group $group, string $token)
    {
        $groupUser = GroupUser::where('group_id', $group->id)
            ->where('token', $token)
            ->where('token_expires_at', '>', now())
            ->firstOrFail();

        $groupUser->update([
            'status' => UserApprovalEnum::APPROVED->value,
            'token' => null,
            'token_expires_at' => null,
            'used_at' => now(),
        ]);

        try {
            return redirect()->route('groups.show', $group);

        } catch (\Throwable $e) {
            return "You joined to the {$group->name}";
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGroupRequest $request, Group $group)
    {
        //
    }
}
