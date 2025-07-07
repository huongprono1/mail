<?php

namespace App\Broadcasting;

use Illuminate\Notifications\Notification;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotChannel
{
    /**
     * @throws TelegramSDKException
     */
    public function send($notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toTelegram')) {
            return;
        }

        $messageData = $notification->toTelegram($notifiable);

        $chatId = $notifiable->routeNotificationFor('telegram');
        if (! $chatId) {
            return;
        }
        try {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => $messageData['text'],
                'parse_mode' => $messageData['parse_mode'] ?? 'Markdown',
                'reply_markup' => $messageData['reply_markup'] ?? null,
            ]);
        } catch (\Exception $e) {
            \Log::warning('Send Telegram error: '.$e->getMessage(), [
                'file' => $e->getLine(),
                'line' => $e->getFile(),
                'error' => $e,
                'messageData' => $messageData,
            ]);
        }
    }
}
