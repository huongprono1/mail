<?php

namespace App\Filament\Resources\NetflixCodeResource\Pages;

use App\Filament\Resources\NetflixCodeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNetflixCodes extends ListRecords
{
    protected static string $resource = NetflixCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
