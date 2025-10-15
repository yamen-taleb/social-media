<?php

namespace App\Http\Controllers;

use App\Http\Requests\addMemberRequest;
use App\Http\Requests\SearchUserGroupRequest;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Requests\ValidateImageRequest;
use App\Http\Resources\GroupPageResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserGroupResource;
use App\Models\Group;
use App\Models\User;
use App\Services\GroupService;
use App\Services\PostService;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class GroupController extends Controller
{
    public function __construct(public GroupService $groupService, public PostService $postService)
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
        $isAdmin = $group->isAdmin();
        $posts = $this->postService
            ->posts()
            ->where('group_id', $group->id)
            ->paginate(5);


        return Inertia::render('Groups/View', [
            'group' => new GroupPageResource($group),
            'requests' => $isAdmin ? Inertia::scroll(fn(
            ) => UserGroupResource::collection($group->usersRequests()->paginate(20))) : null,
            'members' => Inertia::scroll(fn(
            ) => UserGroupResource::collection($group->members()->withPivot('role')->paginate(20))),
            'posts' => Inertia::scroll(fn() => PostResource::collection($posts))
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
        ]);
    }

    public function acceptInvitation(Group $group, string $token)
    {
        $this->groupService->acceptInvitation($group, $token);

        return redirect()->route('groups.show', $group);
    }

    public function join(Group $group, User $user)
    {
        $this->groupService->join($group, $user);

        return back();
    }

    public function handleRequest(Group $group, User $user)
    {
        $action = request()->input('action');
        $this->groupService->handleRequest($group, $user, $action);
        return back();
    }

    public function updateRole(Group $group, User $user, UpdateRoleRequest $updateRoleRequest)
    {
        $this->groupService->updateRole($group, $user, $updateRoleRequest);
        return back();
    }

    public function update(Group $group, UpdateGroupRequest $updateGroupRequest)
    {
        $group->update($updateGroupRequest->validated());
        return back();
    }

    public function removeMember(Group $group, User $user)
    {
        $this->groupService->removeMember($group, $user);
        return back();
    }
}
