<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getFormSchema(): array
    {
        return [
            \Filament\Forms\Components\TextInput::make('title')->required()->maxLength(255),
            \Filament\Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
            \Filament\Forms\Components\Textarea::make('content')->required()->rows(8),
            \Filament\Forms\Components\TextInput::make('cover_image')->label('Ảnh bìa')->nullable(),
            \Filament\Forms\Components\TextInput::make('meta_title')->label('Meta Title')->nullable(),
            \Filament\Forms\Components\Textarea::make('meta_description')->label('Meta Description')->nullable(),
            \Filament\Forms\Components\TextInput::make('meta_keywords')->label('Meta Keywords')->nullable(),
            \Filament\Forms\Components\Toggle::make('is_published')->label('Xuất bản'),
            \Filament\Forms\Components\DateTimePicker::make('published_at')->label('Ngày xuất bản')->nullable(),
            \Filament\Forms\Components\Select::make('tags')
                ->relationship('tags', 'name')
                ->multiple()
                ->searchable()
                ->preload(),
        ];
    }
}
