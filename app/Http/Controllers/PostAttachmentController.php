<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostAttachmentRequest;
use App\Http\Requests\UpdatePostAttachmentRequest;
use App\Models\PostAttachment;
use Illuminate\Support\Facades\Storage;

class PostAttachmentController extends Controller
{
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
    public function store(StorePostAttachmentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PostAttachment $postAttachment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PostAttachment $postAttachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostAttachmentRequest $request, PostAttachment $postAttachment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PostAttachment $postAttachment)
    {
        //
    }

    public function download(PostAttachment $postAttachment)
    {
        return response()->download(Storage::disk('public')->path($postAttachment->path));
    }
}
