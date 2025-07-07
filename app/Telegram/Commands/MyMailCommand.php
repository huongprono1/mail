<?php

namespace App\Telegram\Commands;

use App\Filament\App\Pages\Home;
use App\Filament\App\Pages\Inbox;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Commands\Command;

class MyMailCommand extends Command
{
    protected string $name = 'emails';

    protected string $description = 'List your emails';

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

        $reply = '';
        foreach ($user->mails as $mail) {
            $reply .= $mail->email."\n";
        }
        $count = $user->mails()->count();
        $this->replyWithMessage([
            'text' => "📨 You have {$count} mails:\n".$reply,
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => '📨 Get new mail', 'url' => Home::getUrl(panel: 'app')],
                        ['text' => '📬 Inbox', 'url' => Inbox::getUrl(panel: 'app')],
                    ],
                ],
            ]),
        ]);
    }
}
