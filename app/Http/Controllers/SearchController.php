<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupSearchResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserSearchResource;
use App\Services\GroupService;
use App\Services\PostService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function __construct(
        public PostService $postService,
        public UserService $userService,
        public GroupService $groupService
    ) {

    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $search, Request $request)
    {
        $users = $this->userService->search($search)->paginate(3);
        if ($request->wantsJson() && $request->type === 'users') {
            return response()->json([
                'data' => UserSearchResource::collection($users)->toArray($request),
                'links' => [
                    'next' => $users->nextPageUrl(),
                ],
            ]);
        }

        $groups = $this->groupService->search($search)->paginate(3);
        if ($request->wantsJson() && $request->type === 'groups') {
            return response()->json([
                'data' => GroupSearchResource::collection($groups)->toArray($request),
                'links' => [
                    'next' => $groups->nextPageUrl(),
                ],
            ]);
        }

        $posts = $this->postService->search($search);

        return Inertia::render('SearchPage', [
            'groups' => GroupSearchResource::collection($groups),
            'users' => UserSearchResource::collection($users),
            'posts' => Inertia::scroll(fn() => PostResource::collection($posts)),
            'search' => $search,
        ]);
    }
}
