<?php

namespace App\Events;

use App\Models\Mail;
use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;

class NewMessageEvent
{
    use Dispatchable;

    public function __construct(
        /**
         * @var Mail $mail Mail object model
         */
        public Mail $mail,
        /**
         * @var Message $message Message object model
         */
        public Message $message
    ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [];
    }
}
