<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ClientAndUser;
use App\Filament\Widgets\ClientBrowserOverview;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class AdminDashboard extends BaseDashboard
{
    use HasFiltersAction, HasFiltersForm;

    protected function getHeaderActions(): array
    {
        return [
            FilterAction::make()
                ->form([
                    DatePicker::make('startDate'),
                    DatePicker::make('endDate'),
                    // ...
                ]),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [

        ];
    }
}
