<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReactionRequest;
use App\Http\Requests\UpdateReactionRequest;
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

    public function react(StoreReactionRequest $request, int $id)
    {
        $data = $request->validated();
        $reaction = $this->reactionService->reaction($id, $data['model']);
        $hasReaction = false;

        if ($reaction) {
            $reaction->delete();
        } else {
            $this->reactionService->create($data['type'], $id, $data['model']);
            $hasReaction = true;
        }

        $count = $this->reactionService->countPostReactions($id, $data['model']);
        return response()->json([
            'num_of_reactions' => $count,
            'current_user_has_reaction' => $hasReaction,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
}
