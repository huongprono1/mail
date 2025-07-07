<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MonthlyApiUsageResource\Pages;
use App\Models\MonthlyApiUsage;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MonthlyApiUsageResource extends Resource
{
    protected static ?string $model = MonthlyApiUsage::class;

    protected static ?string $slug = 'monthly-api-usages';

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected static ?string $navigationGroup = 'Stats';

    protected static ?int $navigationSort = 21;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->description(fn ($record) => $record->user->email)
                    ->label('User')
                    ->searchable(['name', 'email']),
                TextColumn::make('year')
                    ->formatStateUsing(fn ($record) => "$record->year/$record->month")
                    ->label('Year/Month'),
                TextColumn::make('count'),
            ])
            ->defaultSort(fn ($query) => $query->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('count', 'desc'))
            ->filters([
                SelectFilter::make('user.name')
                    ->relationship('user', 'name')
                    ->searchable([
                        'name',
                        'email',
                    ])
                    ->label('User')
                    ->placeholder('All Users'),
            ])
            ->actions([
                Action::make('viewUser')
                    ->label('User')
                    ->translateLabel()
                    ->color('info')
                    ->url(fn ($record) => UserResource::getUrl('view', ['record' => $record->user->id]))
                    ->icon('heroicon-o-user'),
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
            'index' => Pages\ListMonthlyApiUsages::route('/'),
            'create' => Pages\CreateMonthlyApiUsage::route('/create'),
            'edit' => Pages\EditMonthlyApiUsage::route('/{record}/edit'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('user.name')->label('User'),
                TextEntry::make('month')->label('Month'),
                TextEntry::make('count'),
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
