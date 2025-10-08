<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Services\PostService;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __construct(public PostService $postService)
    {
    }

    public function index()
    {
        $posts = $this->postService->posts();

        return Inertia::render('Home', [
            'posts' => Inertia::scroll(fn() => PostResource::collection($posts)),
        ]);
    }
}
