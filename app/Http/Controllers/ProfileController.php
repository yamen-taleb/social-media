<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\ValidateImageRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserGroupResource;
use App\Http\Resources\UserResource;
use App\Models\Follower;
use App\Models\User;
use App\Services\ProfileImageService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function __construct(public ProfileImageService $profileImageService)
    {
    }

    public function index(User $user)
    {
        $user->loadCount(['followers']);
        $user->load(['followers', 'following', 'posts']);

        $isCurrentUserFollower = false;
        if (!Auth::guest()) {
            $isCurrentUserFollower = (new Follower())->isFollowing($user->id);
        }

        return Inertia::render('Profile/View', [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => session('status'),
            'user' => new UserResource($user),
            'isCurrentUserFollower' => $isCurrentUserFollower,
            'followersCount' => $user->followers_count,
            'followers' => Inertia::scroll(fn() => UserGroupResource::collection($user->followers()->paginate(10))),
            'following' => Inertia::scroll(fn() => UserGroupResource::collection($user->following()->paginate(10))),
            'posts' => Inertia::scroll(fn() => PostResource::collection($user->posts()->paginate(5))),
        ]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updateCover(ValidateImageRequest $request)
    {
        $this->profileImageService->update($request->file('image'), 'cover_path', 'covers');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile', $request->user()->username);
    }

    public function updateAvatar(ValidateImageRequest $request)
    {
        $this->profileImageService->update($request->file('image'), 'avatar_path', 'avatars');
    }
}
