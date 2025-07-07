<?php

namespace App\Listeners;

use App\Enums\UserPlanStatus;
use App\Events\PaypalSuccessEvent;
use App\Services\UserFeatureService;
use Telegram\Bot\Laravel\Facades\Telegram;

class PaypalSuccessListener
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
    public function handle(PaypalSuccessEvent $event): void
    {
        $userPlan = $event->userPlan;
        $paymentTransaction = $event->paymentTransaction;

        // active
        $userPlan->status = UserPlanStatus::Active;
        $userPlan->started_at = now();
        $userPlan->payment_transaction_id = $paymentTransaction->id;

        if ($userPlan->billing_cycle == 'monthly') {
            $userPlan->expired_at = now()->addMonth();
        } elseif ($userPlan->billing_cycle == 'yearly') {
            $userPlan->expired_at = now()->addYear();
        }
        $userPlan->save();

        (new UserFeatureService($userPlan->user))->warmCache();

        // notify
        if (setting('site.telegram_notify_chat_id', null)) {
            $thread_id = setting('site.telegram_notify_thread_id', null);
            $payload = [
                'chat_id' => setting('site.telegram_notify_chat_id'),
                'text' => "📣 PAYMENT SUCCESSFUL \n\n User: `{$userPlan->user->name}`\n Email: `{$userPlan->user->email}`\n Plan: `{$userPlan->plan->name}` ({$userPlan->billing_cycle})\n Gateway: paypal",
                'parse_mode' => 'Markdown',
            ];
            if ($thread_id) {
                $payload['message_thread_id'] = $thread_id;
            }
            Telegram::sendMessage($payload);
        }

    }
}
