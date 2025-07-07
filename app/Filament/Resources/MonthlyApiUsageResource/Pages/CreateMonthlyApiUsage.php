<?php

namespace App\Filament\Resources\MonthlyApiUsageResource\Pages;

use App\Filament\Resources\MonthlyApiUsageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMonthlyApiUsage extends CreateRecord
{
    protected static string $resource = MonthlyApiUsageResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
