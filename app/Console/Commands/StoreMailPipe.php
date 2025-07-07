<?php

namespace App\Console\Commands;

use App\Events\NewMessageEvent;
use App\Models\Mail;
use App\Services\MailService;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;

class StoreMailPipe extends Command
{
    protected $signature = 'mail:store {sender} {recipient}';

    protected $description = 'Receive mail from Postfix via pipe and store into database';

    /**
     * @throws BindingResolutionException
     */
    public function handle()
    {
        $sender = $this->argument('sender');
        $recipient = $this->argument('recipient');

        // Read the raw email from STDIN
        $rawEmail = '';
        while (! feof(STDIN)) {
            $rawEmail .= fgets(STDIN);
        }

        Log::info("New mail from {$sender} -> {$recipient}");

        // Lookup the Mail entry
        $mail = Mail::getMailWithDomainActive($recipient);

        if (! $mail) {
            Log::warning("Recipient {$recipient} not found in DB");

            return 1;
        }

        // Parse the raw email
        $mailService = new MailService($mail, $rawEmail, $sender, $recipient);
        $message = $mailService->storeMessage();

        // send notification
        event(new NewMessageEvent($mail, $message));

        Log::info("Saved mail <{$mailService->getSubject()}><{$recipient}>");

        return 0;
    }
}
