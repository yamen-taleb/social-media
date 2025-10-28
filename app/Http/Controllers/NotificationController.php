<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationsResource;

class NotificationController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
           $notifications = auth()->user()->notifications()->latest()->paginate(5);
            return response()->json([
                'data' => NotificationsResource::collection($notifications),
                'links' => $notifications->links(),
                'meta' => [
                    'current_page' => $notifications->currentPage(),
                ]
            ], 200);
        }
    }

    public function destroy($notificationId)
    {
        $notification = auth()->user()->notifications()->findOrFail($notificationId);

        $notification->delete();

        return response()->noContent();
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return response()->noContent();
    }
}
