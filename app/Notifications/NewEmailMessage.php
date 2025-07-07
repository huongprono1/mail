<?php

namespace App\Notifications;

use App\Broadcasting\TelegramBotChannel;
use App\Filament\App\Pages\ReadMail;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use NotificationChannels\Fcm\FcmMessage;

class NewEmailMessage extends Notification
{
    use Queueable;

    protected Message $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [
            //            FcmChannel::class,
            TelegramBotChannel::class,
        ];
    }

    public function toFcm($notifiable): FcmMessage
    {
        return (new FcmMessage(notification: new \NotificationChannels\Fcm\Resources\Notification(
            title: "{$this->message->sender_name} <{$this->message->from}>",
            body: $this->message->subject,
            //            image: 'https://laravel-notification-channels.com/logo.svg'
        )))
            ->data([
                'type' => 'email',
                'id' => (string) $this->message->id,
            ])
            ->custom([
                'android' => [
                    'notification' => [
                        'color' => '#2dd4bf',
                        'sound' => 'default',
                    ],
                    'fcm_options' => [
                        'analytics_label' => 'email',
                    ],
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'sound' => 'email',
                        ],
                    ],
                    'fcm_options' => [
                        'analytics_label' => 'new_mail',
                    ],
                ],
            ]);
    }

    public function toTelegram($notifiable): array
    {
        //        $locale = app()->getLocale();
        $url = URL::signedRoute(ReadMail::getRouteName('app'), ['locale' => 'en', 'slug' => $this->message->slug, 'telegram_id' => $notifiable->telegram_id], expiration: now()->addMinutes(15));

        return [
            'text' => "📨 <b>{$this->message->subject}</b>\n"
                ."From: <code>{$this->message->sender_name}</code> &lt;{$this->message->from}&gt;\n"
                ."To: <code>{$this->message->email->email}</code>\n",
            'parse_mode' => 'html',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => '📬 Read Message', 'url' => $url],
                    ],
                ],
            ]),
        ];
    }

    public function shouldQueue()
    {
        return true;
    }
}
