<?php

namespace App\Services;

use Illuminate\Support\Facades\Event;
use App\Events\NotificationSent;
use App\Models\Notification;

class NotificationService
{
    public function notifyStatusChanged(int $userId, int $appealId, string $statusName): void
    {
        $notification = Notification::create([
            'user_id' => $userId,
            'type' => 'status_changed',
            'message' => "Статус вашего обращения №$appealId изменён на '$statusName'.",
            'related_id' => $appealId,
        ]);

        Event::dispatch(new NotificationSent($notification->toArray()));
    }

    public function notifyNewAppeal(int $appealId): void
    {
        $adminUsers = \App\Models\User::where('role_id', 2)->get();

        foreach ($adminUsers as $admin) {
            $notification = Notification::create([
                'user_id' => $admin->id,
                'type' => 'new_appeal',
                'message' => "Новое обращение создано. ID: $appealId.",
                'related_id' => $appealId,
            ]);

            Event::dispatch(new NotificationSent($notification->toArray()));
        }
    }
}
