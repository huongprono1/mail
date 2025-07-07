<?php

namespace App\Listeners;

use Telegram\Bot\Laravel\Facades\Telegram;

class UptimeCheckFailed
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(\Spatie\UptimeMonitor\Events\UptimeCheckFailed $event): void
    {
        Telegram::sendMessage([
            'chat_id' => setting('site.telegram_notify_chat_id'),
            'text' => "🆘 WARNING!!! \n\n Site status {$event->monitor->uptime_status} at {$event->monitor->uptime_last_check_date}",
        ]);
    }
}
