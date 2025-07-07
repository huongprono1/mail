<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Mail;
use App\Models\Message;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class SystemStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Emails', Number::abbreviate(Mail::count()))->icon('heroicon-o-at-symbol')
                ->description('Today: '.Number::abbreviate(Mail::whereDate('created_at', now()->format('Y-m-d'))->count())),
            Stat::make('Messages', Number::abbreviate(Message::count()))->icon('heroicon-o-envelope')
                ->description('Today: '.Number::abbreviate(Message::whereDate('created_at', now()->format('Y-m-d'))->count())),
            Stat::make('Users', Number::abbreviate(User::count()))->icon('heroicon-o-users')
                ->description('Today : '.Number::abbreviate(User::whereDate('created_at', now()->format('Y-m-d'))->count())),
            Stat::make('Clients', Number::abbreviate(Client::count()))->icon('heroicon-o-computer-desktop')
                ->description('Today: '.Number::abbreviate(Client::whereDate('created_at', now()->format('Y-m-d'))->count())),
        ];
    }
}
