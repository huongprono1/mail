<?php

namespace App\Filament\Resources\TagResource;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $title = 'Bài viết';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\TextColumn::make('user.name')->label('Tác giả'),
                Tables\Columns\IconColumn::make('is_published')->boolean()->label('Xuất bản'),
                Tables\Columns\TextColumn::make('published_at')->dateTime()->label('Ngày xuất bản'),
            ]);
    }
}
