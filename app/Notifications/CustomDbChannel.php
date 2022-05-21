<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
// use App\Models\Notification as ModelNotification;

class CustomDbChannel
{
    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toArray($notifiable);
        return true;
    }
}