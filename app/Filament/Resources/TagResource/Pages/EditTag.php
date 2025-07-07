<?php

namespace App\Filament\Resources\TagResource\Pages;

use App\Filament\Resources\TagResource;
use Filament\Resources\Pages\EditRecord;

class EditTag extends EditRecord
{
    protected static string $resource = TagResource::class;

    protected function getFormSchema(): array
    {
        return [
            \Filament\Forms\Components\TextInput::make('name')->required()->maxLength(255),
            \Filament\Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
        ];
    }
}
