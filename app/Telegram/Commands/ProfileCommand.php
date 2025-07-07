<?php

namespace App\Telegram\Commands;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Commands\Command;

class ProfileCommand extends Command
{
    protected string $name = 'profile';

    protected string $description = 'Your profile at tempmail.id.vn';

    public function handle()
    {
        $telegram_id = $this->getUpdate()->getMessage()->from->id;
        $user = User::where('telegram_id', $telegram_id)->first();
        if (! $user) {
            Log::error('User not found with telegram id: '.$telegram_id);
            $this->replyWithMessage([
                'text' => "Your telegram id `$telegram_id` not confirmed. Please go to https://tempmail.id.vn/profile and save it to Telegram ID field.",
                'parse_mode' => 'Markdown',
            ]);

            return;
        }

        $apiTokenCounts = $user->personalAccessTokens()->count();
        $twoFactoryEnable = $user->two_factor_confirmed_at ? 'Yes' : 'No';

        $this->replyWithMessage([
            'text' => "👋 Welcome {$user->name},\n - Your Telegram ID: {$user->telegram_id}\n - Your email: {$user->email}\n - API Tokens: {$apiTokenCounts}\n - 2FA Enable: {$twoFactoryEnable}",
            'parse_mode' => 'Markdown',
        ]);
    }
}
