<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupResource;
use App\Http\Resources\PostResource;
use App\Services\GroupService;
use App\Services\PostService;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __construct(public PostService $postService, public GroupService $groupService)
    {
    }

    public function index()
    {
        $posts = $this->postService->posts()->paginate(5);

        $groups = $this->groupService->groups();

        return Inertia::render('Home', [
            'posts' => Inertia::scroll(fn() => PostResource::collection($posts)),
            'groups' => GroupResource::collection($groups)
        ]);
    }
}
