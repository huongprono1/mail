<?php

namespace App\Filament\Resources\ApiRequestLogResource\Pages;

use App\Filament\Resources\ApiRequestLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApiRequestLogs extends ListRecords
{
    protected static string $resource = ApiRequestLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
