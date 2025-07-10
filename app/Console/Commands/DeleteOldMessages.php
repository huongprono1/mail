<?php

namespace App\Console\Commands;

use App\Models\Message;
use App\Models\User;
use App\Settings\MailBackendSetting;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;

class DeleteOldMessages extends Command
{
    protected $signature = 'message:delete-old';

    protected $description = 'Delete old messages older than 30 days';

    public function handle()
    {
        $minutes = app(MailBackendSetting::class)->message_expiration_days ?? 7;
        $cutoff = Carbon::now()->subMinutes($minutes);
        $messages = Message::where('created_at', '<', $cutoff)->get();
        $count = $messages->count();
        foreach ($messages as $message) {
            $message->forceDelete();
        }
        $user = User::findOrFail(1);
        Notification::make()
            ->title('Xóa message định kỳ')
            ->body("Đã xóa {$count} message quá $minutes phut.")
            ->success()
            ->sendToDatabase($user, isEventDispatched: true);
        $this->info("Đã xóa {$count} message quá $minutes phut.");
    }
}
