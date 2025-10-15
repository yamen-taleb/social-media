<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCommentsRequest;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Services\CommentService;

class CommentController extends Controller
{
    public function __construct(public CommentService $commentService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetCommentsRequest $getCommentsRequest)
    {
        $data = $getCommentsRequest->validated();

        $comments = $this->commentService->comments($data);

        return response()->json([
                'comments' => CommentResource::collection($comments),
                'paginationLinks' => pagination_links($comments)
            ]
            , 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        $data = $request->validated();

        $comment = $this->commentService->create($data);

        return response()->json(new CommentResource($comment), 201);
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
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $body = $request->validated()['body'];

        $comment->update([
            'body' => $body
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $this->commentService->delete($comment);
        return back();
    }
}
