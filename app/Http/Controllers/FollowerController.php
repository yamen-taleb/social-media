<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFollowerRequest;
use App\Http\Requests\UpdateFollowerRequest;
use App\Models\Follower;
use App\Services\FollowerService;

class FollowerController extends Controller
{
    public function __construct(public FollowerService $followerService)
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
    public function store(StoreFollowerRequest $request)
    {
        $user_id = $request->input('user_id');

        $isCurrentUserFollower = $this->followerService->store($user_id);

        return response()->json(['isCurrentUserFollower' => $isCurrentUserFollower]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Follower $follower)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Follower $follower)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFollowerRequest $request, Follower $follower)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Follower $follower)
    {
        //
    }
}
