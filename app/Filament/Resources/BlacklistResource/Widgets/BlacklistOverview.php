<?php

namespace App\Filament\Resources\BlacklistResource\Widgets;

use App\Models\Blacklist;
use App\Models\BlacklistHistory;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class BlacklistOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $startDate = Carbon::now()->subDays(30);

        $totals = Blacklist::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type');

        $totalHistory = BlacklistHistory::whereHas('blacklist', function ($query) {
            $query->whereIn('type', ['domain', 'keyword']);
        })
            ->selectRaw('blacklists.type, COUNT(*) as count')
            ->join('blacklists', 'blacklist_histories.blacklist_id', '=', 'blacklists.id')
            ->groupBy('blacklists.type')
            ->pluck('count', 'type');

        $historyTrendsDomain = BlacklistHistory::whereHas('blacklist', function ($query) {
            $query->where('type', 'domain');
        })
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count')
            ->toArray();

        $historyTrendsKeyword = BlacklistHistory::whereHas('blacklist', function ($query) {
            $query->where('type', 'keyword');
        })
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count')
            ->toArray();

        return [
            Stat::make('Domains', Number::abbreviate($totals['domain'] ?? 0))
                ->description('Total domains in blacklist')
                ->icon('heroicon-o-globe-alt'),
            Stat::make('Keywords', Number::abbreviate($totals['keyword'] ?? 0))
                ->description('Total keywords in blacklist')
                ->icon('heroicon-o-shield-exclamation'),

            Stat::make('Domain times', Number::abbreviate($totalHistory['domain'] ?? 0))
                ->description('Times blocked domain in 30 days')
                ->icon('heroicon-c-arrow-trending-up')
                ->chart($historyTrendsDomain)
                ->color('warning'),

            Stat::make('Keyword times', Number::abbreviate($totalHistory['keyword'] ?? 0))
                ->description('Times blocked keyword in 30 days')
                ->icon('heroicon-c-arrow-trending-up')
                ->chart($historyTrendsKeyword)
                ->color('danger'),
        ];
    }
}
