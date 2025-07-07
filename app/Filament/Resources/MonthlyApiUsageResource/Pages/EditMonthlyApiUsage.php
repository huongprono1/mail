<?php

namespace App\Filament\Resources\MonthlyApiUsageResource\Pages;

use App\Filament\Resources\MonthlyApiUsageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMonthlyApiUsage extends EditRecord
{
    protected static string $resource = MonthlyApiUsageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
