<?php

namespace App\Filament\Resources\MailResource\Pages;

use App\Filament\Resources\MailResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMails extends ListRecords
{
    protected static string $resource = MailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
