<?php

namespace App\Listeners;

use App\Enums\UserPlanStatus;
use App\Events\SePayWebhookEvent;
use App\Models\UserPlan;
use App\Services\UserFeatureService;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Laravel\Facades\Telegram;

class SePayWebhookListener
{
    public function __construct() {}

    /**
     * @throws TelegramSDKException
     */
    public function handle(SePayWebhookEvent $event): void
    {
        // Xử lý tiền vào tài khoản
        if ($event->sePayWebhookData->transferType === 'in') {
            // Trường hợp $info user_plans id
            $userPlan = UserPlan::query()->where('id', intval($event->info))->first();
            if ($userPlan instanceof UserPlan) {
                $userPlan->status = UserPlanStatus::Active;
                $userPlan->started_at = now();
                $userPlan->payment_transaction_id = $event->paymentTransaction->id;
                if ($userPlan->billing_cycle == 'monthly') {
                    // monthy
                    $userPlan->expired_at = now()->addMonth();
                } elseif ($userPlan->billing_cycle == 'yearly') {
                    // yearly
                    $userPlan->expired_at = now()->addYear();
                }
                $userPlan->save();

                (new UserFeatureService($userPlan->user))->warmCache();

                // notify
                if (setting('site.telegram_notify_chat_id', null)) {
                    $thread_id = setting('site.telegram_notify_thread_id', null);
                    $payload = [
                        'chat_id' => setting('site.telegram_notify_chat_id'),
                        'text' => "📣 PAYMENT SUCCESSFUL \n\n User: `{$userPlan->user->name}`\n Email: `{$userPlan->user->email}`\n Plan: `{$userPlan->plan->name}` ({$userPlan->billing_cycle})",
                        'parse_mode' => 'Markdown',
                    ];
                    if ($thread_id) {
                        $payload['message_thread_id'] = $thread_id;
                    }
                    Telegram::sendMessage($payload);
                }
            }
        }
    }
}
