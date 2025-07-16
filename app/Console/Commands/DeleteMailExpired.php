<?php

namespace App\Console\Commands;

use App\Models\Mail;
use App\Settings\MailBackendSetting;
use Illuminate\Console\Command;

class DeleteMailExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:delete-mail-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete mail not owner and expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $minutes = app(MailBackendSetting::class)->mail_expiration_minutes ?? 7;

        $count = Mail::query()
            ->whereDate('updated_at', '<', now()->subMinutes($minutes))
            ->forceDelete();

        $this->info("Delete $count mail not owner expired.");
    }
}
