<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    protected string $name = 'start';

    protected string $description = 'Start bot';

    public function handle()
    {
        $username = $this->getUpdate()->getMessage()->from->username;

        $this->replyWithMessage([
            'text' => "Hello {$username}! Welcome to our bot.\nNow, you can get new inbox in this bot.",
        ]);
    }
}
