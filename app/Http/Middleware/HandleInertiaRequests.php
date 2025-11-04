<?php

namespace App\Http\Middleware;

use App\Http\Requests\StorePostRequest;
use App\Http\Resources\NotificationsResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user() ? Inertia::defer(fn() => new UserResource($request->user())) : null,
            ],
            'attachmentExtensions' => explode(',', StorePostRequest::$attachmentExtensions),
            'notifications' => auth()->check() ? Inertia::defer(fn() =>NotificationsResource::collection(auth()->user()
                ->notifications()
                ->latest()
                ->paginate(5))) : null,
            'unread' => auth()->check() ? Inertia::defer(fn() => auth()->user()->unreadNotifications->count()) : 0,
        ];
    }
}
