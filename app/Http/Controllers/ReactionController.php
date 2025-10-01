<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReactionRequest;
use App\Http\Requests\UpdateReactionRequest;
use App\Models\Post;
use App\Models\Reaction;
use App\Services\ReactionService;

class ReactionController extends Controller
{
    public function __construct(public ReactionService $reactionService)
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReactionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Reaction $reaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reaction $reaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReactionRequest $request, Reaction $reaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reaction $reaction)
    {
        //
    }

    public function react(StoreReactionRequest $request, int $postId)
    {
        $type = $request->get('type');
        $reaction = $this->reactionService->reaction($postId);
        $hasReaction  = false;

        if ($reaction)
            $reaction->delete();
        else {
            $this->reactionService->create($type, $postId);
            $hasReaction = true;
        }

        $count = $this->reactionService->countPostReactions($postId);
        return response()->json([
            'num_of_reactions' => $count,
            'current_user_has_reaction' => $hasReaction,
        ]);
    }
}
