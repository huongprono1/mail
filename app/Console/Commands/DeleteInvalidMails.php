<?php

namespace App\Console\Commands;

use App\Models\Mail;
use Illuminate\Console\Command;

class DeleteInvalidMails extends Command
{
    protected $signature = 'mail:delete-invalid';

    protected $description = 'Xóa các mail có 2 ký tự @ trong field email';

    public function handle()
    {
        $count = Mail::whereRaw("LENGTH(email) - LENGTH(REPLACE(email, '@', '')) >= 2")->count();

        if ($count === 0) {
            $this->info('Không có mail nào cần xóa.');

            return 0;
        }

        $deleted = Mail::whereRaw("LENGTH(email) - LENGTH(REPLACE(email, '@', '')) >= 2")->delete();

        $this->info("Đã xóa {$deleted} mail có 2 ký tự @ trong email.");

        return 0;
    }
}
