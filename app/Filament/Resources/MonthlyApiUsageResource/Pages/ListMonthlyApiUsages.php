<?php

namespace App\Filament\Resources\MonthlyApiUsageResource\Pages;

use App\Filament\Resources\MonthlyApiUsageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMonthlyApiUsages extends ListRecords
{
    protected static string $resource = MonthlyApiUsageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
