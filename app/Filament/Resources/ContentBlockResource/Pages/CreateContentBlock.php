<?php

namespace App\Filament\Resources\ContentBlockResource\Pages;

use App\Filament\Resources\ContentBlockResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateContentBlock extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = ContentBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Actions\LocaleSwitcher::make(),
        ];
    }
}
