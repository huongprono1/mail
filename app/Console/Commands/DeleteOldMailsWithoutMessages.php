<?php

namespace App\Console\Commands;

use App\Models\Mail;
use App\Settings\MailBackendSetting;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteOldMailsWithoutMessages extends Command
{
    protected $signature = 'mail:delete-old-without-messages';

    protected $description = 'Delete old mails without messages in 7 days';

    public function handle()
    {
        $minutes = app(MailBackendSetting::class)->message_expiration_days ?? 7;

        $cutoff = Carbon::now()->subMinutes($minutes);
        $mails = Mail::whereDoesntHave('messages')
            ->where('created_at', '<', $cutoff)
            ->get();
        $count = $mails->count();
        foreach ($mails as $mail) {
            $mail->forceDelete();
        }
        $this->info("Đã xóa {$count} mail không có message nào trong $minutes phút.");
    }
}
