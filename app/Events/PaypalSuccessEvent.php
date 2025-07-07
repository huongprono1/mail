<?php

namespace App\Events;

use App\Models\PaymentTransaction;
use App\Models\UserPlan;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaypalSuccessEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        /** @var UserPlan $userPlan */
        public UserPlan $userPlan,
        /**
         * @var PaymentTransaction info The payment transaction associated with the event.
         */
        public PaymentTransaction $paymentTransaction
    ) {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
