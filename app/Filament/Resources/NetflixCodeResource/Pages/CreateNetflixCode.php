<?php

namespace App\Filament\Resources\NetflixCodeResource\Pages;

use App\Filament\Resources\NetflixCodeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNetflixCode extends CreateRecord
{
    protected static string $resource = NetflixCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
