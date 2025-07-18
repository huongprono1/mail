<?php

namespace App\Filament\Resources\MailResource\Pages;

use App\Filament\Resources\MailResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMail extends CreateRecord
{
    protected static string $resource = MailResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
