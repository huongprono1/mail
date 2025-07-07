<?php

namespace App\Filament\Resources\NetflixCodeResource\Pages;

use App\Filament\Resources\NetflixCodeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNetflixCode extends EditRecord
{
    protected static string $resource = NetflixCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
