<?php

namespace App\Console\Commands;

use App\Models\Mail;
use App\Models\User;
use Filament\Notifications\Notification;
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
    protected $description = 'Delete mail not owner and expired 7 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = Mail::query()
            ->whereDate('updated_at', '<', now()->subDays(7))
            ->whereNull('user_id')
            ->delete();

        // notify admin
        if ($count) {
            $user = User::findOrFail(1);
            Notification::make()
                ->title('Xóa mail người dùng khách quá 7 ngày')
                ->body("Đã xóa $count mail của người dùng không đăng nhập.")
                ->success()
                ->sendToDatabase($user, isEventDispatched: true);
        }
        $this->info("Delete $count mail not owner expired.");
    }
}
