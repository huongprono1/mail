<?php

namespace App\Console\Commands;

use App\Models\ApiRequestLog;
use Illuminate\Console\Command;

class CleanApiLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:log:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean api log older than 30 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Cleaning api log older than 30 days');
        $result = ApiRequestLog::where('created_at', '<=', now()->subDays(30))->delete();
        $this->info('Deleted ' . $result . ' api logs');
    }
}
