<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatOverview extends BaseWidget
{
    public ?User $record = null;

    protected function getStats(): array
    {
        $messageCount = $this->record?->messages()->count() ?? 0;
        $currentPlan = $this->record?->currentPlan;

        return [
            Stat::make('Email', $this->record?->mails()->count() ?? 0)
                ->icon('heroicon-o-envelope')
                ->description($messageCount.' messages'),
            Stat::make('Plan', $currentPlan?->plan?->name ?? '-')
                ->icon('heroicon-o-star')
                ->description($currentPlan ? 'Expired '.$currentPlan->started_at->format('d-m-Y').' to '.($currentPlan->expired_at?->format('d-m-Y') ?: '-') : ''),

            Stat::make('Sessions', $this->record?->sessions()->count() ?? 0)
                ->icon('heroicon-o-globe-alt'),
            Stat::make('API Tokens', $this->record?->tokens()->count() ?? 0)
                ->icon('heroicon-o-key'),
        ];
    }
}
