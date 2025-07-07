<?php

namespace App\Filament\Resources;

use App\Enums\BlacklistType;
use App\Filament\Resources\BlacklistResource\Pages;
use App\Filament\Resources\BlacklistResource\RelationManagers\HistoriesRelationManager;
use App\Models\Blacklist;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class BlacklistResource extends Resource
{
    protected static ?string $model = Blacklist::class;

    protected static ?string $slug = 'blacklists';

    protected static ?string $navigationIcon = 'heroicon-o-shield-exclamation';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('value')
                    ->required()
                    ->unique(Blacklist::class, 'value'),
                ToggleButtons::make('type')
                    ->options(BlacklistType::class)
                    ->required()
                    ->inline()
                    ->live()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('value')->searchable(),
                TextColumn::make('type'),
                TextColumn::make('histories_count')
                    ->label('Histories')
                    ->counts('histories')
                    ->badge()
                    ->color('danger')
                    ->icon('heroicon-o-shield-exclamation')
                    ->sortable(),
                ToggleColumn::make('active'),
            ])
            ->defaultSort('histories_count', 'desc')
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlacklists::route('/'),
            'create' => Pages\CreateBlacklist::route('/create'),
            'edit' => Pages\EditBlacklist::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            HistoriesRelationManager::class,
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['value'];
    }
}
