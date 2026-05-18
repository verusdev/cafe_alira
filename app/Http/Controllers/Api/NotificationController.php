<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function index(): JsonResponse
    {
        $notifications = auth()->user()->notifications()->paginate(20);

        return response()->json([
            'notifications' => $notifications->items(),
            'unread_count' => auth()->user()->unreadNotifications->count(),
            'meta' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'total' => $notifications->total(),
            ],
        ]);
    }

    public function read(string $id): JsonResponse
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['message' => 'Уведомление прочитано']);
    }

    public function readAll(): JsonResponse
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['message' => 'Все уведомления прочитаны']);
    }
}
