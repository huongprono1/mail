<?php

namespace App\Events;

use App\Http\Datas\SePayWebhookData;
use App\Models\PaymentTransaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;

class SePayWebhookEvent
{
    use Dispatchable;

    public function __construct(
        /**
         * @var string $info Information related to the event.
         */
        public string $info,
        /**
         * @var SePayWebhookData $sePayWebhookData The webhook data from SePay.
         */
        public SePayWebhookData $sePayWebhookData,
        /**
         * @var PaymentTransaction info The payment transaction associated with the event.
         */
        public PaymentTransaction $paymentTransaction
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
