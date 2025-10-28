<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostAttachmentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('home');

Route::get('/user/{user:username}', [ProfileController::class, 'index'])
    ->name('profile');
Route::get('/post-attachments/{postAttachment}',
    [PostAttachmentController::class, 'download'])->name('post-attachments.download');

Route::middleware('auth')->group(function () {
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/cover', [ProfileController::class, 'updateCover'])->name('profile.cover');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::post('/followers', [FollowerController::class, 'store'])->name('followers.store');

    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::put('/posts/{post:id}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::post('posts/pin/{post}', [PostController::class, 'pin'])->name('posts.pin');

    Route::post('/reactions/{id}', [ReactionController::class, 'react'])->name('reactions.react');

    Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');

    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/{group:slug}', [GroupController::class, 'show'])->name('groups.show');
    Route::post('/groups/cover/{group:slug}', [GroupController::class, 'updateCover'])->name('groups.cover');
    Route::post('/groups/avatar/{group:slug}', [GroupController::class, 'updateAvatar'])->name('groups.avatar');
    Route::get('/groups/users/search/{group:slug}',
        [GroupController::class, 'searchUsers'])->name('groups.users.search');
    Route::post('/groups/add-member/{group:slug}', [GroupController::class, 'addMember'])->name('groups.members.add');

    Route::post('/groups/join/{group:slug}/{user}', [GroupController::class, 'join'])->name('groups.members.join');
    Route::post('/groups/handle-request/{group:slug}/{user}',
        [GroupController::class, 'handleRequest'])->name('groups.members.handle-request');
    Route::patch('/groups/members/update-role/{group:slug}/{user}',
        [GroupController::class, 'updateRole'])->name('groups.members.update-role');
    Route::put('/groups/{group:slug}', [GroupController::class, 'update'])->name('groups.update');
    Route::delete('/groups/members/remove/{group:slug}/{user}',
        [GroupController::class, 'removeMember'])->name('groups.members.remove');

    Route::get('/search', [HomeController::class, 'search'])->name('search');
    Route::get('/searchPage/{search}', SearchController::class)->name('searchPage');

    Route::delete('/notifications/{notificationId}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
});

Route::get('/groups/{group}/invitations/accept/{token}', [GroupController::class, 'acceptInvitation'])
    ->name('group.invitation.accept');

require __DIR__.'/auth.php';
