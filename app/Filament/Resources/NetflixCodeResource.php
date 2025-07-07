<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NetflixCodeResource\Pages;
use App\Models\NetflixCode;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NetflixCodeResource extends Resource
{
    protected static ?string $model = NetflixCode::class;

    protected static ?string $slug = 'netflix-codes';

    protected static ?string $navigationIcon = 'heroicon-o-finger-print';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('email_id')
                    ->relationship('email', 'email')
                    ->searchable()
                    ->required(),
                TextInput::make('real_email')
                    ->required(),
                TextInput::make('code')
                    ->required()
                    ->integer(),
                TextInput::make('link')
                    ->required(),
                DatePicker::make('read_at')
                    ->label('Read Date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('real_email'),
                TextColumn::make('email.email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('code')->copyable(),
                IconColumn::make('link')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn ($record) => $record->link ?? '#')
                    ->openUrlInNewTab(),
                TextColumn::make('read_at')
                    ->label('Read Date')
                    ->since(),
                TextColumn::make('created_at'),
            ])
            ->defaultSort('id', 'desc')
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
            'index' => Pages\ListNetflixCodes::route('/'),
            'create' => Pages\CreateNetflixCode::route('/create'),
            'edit' => Pages\EditNetflixCode::route('/{record}/edit'),
        ];
    }
}
