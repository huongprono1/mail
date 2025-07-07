<?php

namespace App\Filament\Resources\ContentBlockResource\Pages;

use App\Filament\Resources\ContentBlockResource;
use Filament\Actions;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContentBlock extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = ContentBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
