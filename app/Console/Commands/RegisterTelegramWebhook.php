<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class RegisterTelegramWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:register-telegram-webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $response = Telegram::setWebhook(['url' => config('telegram.bots.mybot.webhook_url')]);
        $updates = Telegram::getWebhookUpdate();
        $webhookInfo = Telegram::getWebhookInfo();
        $this->info(print_r([
            'set_webhook' => $response,
            'updates' => $updates,
            'webhook_info' => $webhookInfo,
        ], true));
    }
}
