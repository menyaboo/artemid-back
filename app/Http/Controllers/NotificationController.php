<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('is_read')
            ->orderByDesc('created_at')
            ->get();

        return response()->json($notifications);
    }

    public function markAsRead(Request $request, $notificationId)
    {
        $user = Auth::user();

        // Получаем уведомление, проверяем, что оно принадлежит пользователю и не прочитано
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', $user->id)
            ->where('is_read', false)
            ->first();

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Уведомление не найдено или уже прочитано'
            ], 404);
        }

        // Отмечаем как прочитанное
        $notification->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'notification_id' => $notificationId,
        ]);
    }
}
