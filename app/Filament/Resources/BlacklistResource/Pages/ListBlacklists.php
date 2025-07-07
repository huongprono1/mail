<?php

namespace App\Filament\Resources\BlacklistResource\Pages;

use App\Filament\Resources\BlacklistResource;
use App\Filament\Resources\BlacklistResource\Widgets\BlacklistOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBlacklists extends ListRecords
{
    protected static string $resource = BlacklistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            BlacklistOverview::class,
        ];
    }
}
