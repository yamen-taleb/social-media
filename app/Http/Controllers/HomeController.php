<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupResource;
use App\Http\Resources\GroupSearchResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserSearchResource;
use App\Services\GroupService;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __construct(public PostService $postService, public GroupService $groupService, public UserService $userService)
    {
    }

    public function index()
    {
        $user = Auth::user();
        $followingIds = $user->following()->pluck('users.id');
        $groups = $this->groupService->groups();
        $groupIds = $groups->pluck('id');

        $posts = $this->postService->getHomePosts($user, $followingIds, $groupIds)->paginate(5);

        return Inertia::render('Home', [
            'posts' => Inertia::scroll(fn() => PostResource::collection($posts)),
            'groups' => GroupResource::collection($groups)
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $groups = $this->groupService->search($search, 3);
        $users = $this->userService->search($search, 3);

        return response()->json([
            'groups' => GroupSearchResource::collection($groups),
            'users' => UserSearchResource::collection($users),
        ]);
    }
}
