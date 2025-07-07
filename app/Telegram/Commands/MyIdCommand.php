<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;

class MyIdCommand extends Command
{
    protected string $name = 'myid';

    protected string $description = 'Get your chat id';

    public function handle()
    {
        $user_id = $this->getUpdate()->getMessage()->from->id;

        $this->replyWithMessage([
            'text' => "Your chat id is `{$user_id}`.\n Go to https://tempmail.id.vn/profile and save it to Telegram ID field.",
            'parse_mode' => 'Markdown',
        ]);
    }
}
