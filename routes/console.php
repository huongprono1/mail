<?php

use App\Console\Commands\CheckPremiumUserExpired;
use App\Console\Commands\CleanApiLog;
use App\Console\Commands\DeleteInvalidMails;
use App\Console\Commands\DeleteMailExpired;
use App\Console\Commands\DeleteOldMessages;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

//Schedule::command('geoip:update')->hourly();

// Delete expired mail 7 days and not owner
Schedule::command(DeleteMailExpired::class)->everyMinute();

// Delete invalidate mail
Schedule::command(DeleteInvalidMails::class)->daily();

// Delete old messages older than 30 days
Schedule::command(DeleteOldMessages::class)->everyMinute();

// Delete old mails without messages in 30 days
// Schedule::command(DeleteOldMailsWithoutMessages::class)->daily();

// Check premium user expired
Schedule::command(CheckPremiumUserExpired::class)->dailyAt('01:00'); // 1am();

// Clean api log
Schedule::command(CleanApiLog::class)->dailyAt('00:00');
