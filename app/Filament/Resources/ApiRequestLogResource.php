<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApiRequestLogResource\Pages;
use App\Models\ApiRequestLog;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use ValentinMorice\FilamentJsonColumn\JsonInfolist;

class ApiRequestLogResource extends Resource
{
    protected static ?string $model = ApiRequestLog::class;

    protected static ?string $slug = 'api-request-logs';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Stats';

    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->description(fn(ApiRequestLog $record) => $record->user->email)
                    ->searchable(['name', 'email']),
                TextColumn::make('method')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'GET' => 'info',
                        'POST' => 'success',
                        'PUT', 'PATCH' => 'warning',
                        'DELETE' => 'danger',
                        default => 'gray',
                    })
                    ->description(fn(ApiRequestLog $record) => $record->path)
                    ->searchable(['method', 'path']),
                TextColumn::make('status_code')
                    ->badge()
                    ->color(fn(ApiRequestLog $record): string => match ($record->status_code) {
                        200, 201 => 'success',
                        400, 401, 403, 404 => 'warning',
                        500 => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('ip'),
                TextColumn::make('user_agent'),
                TextColumn::make('created_at'),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                // add filter by user
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
                ViewAction::make('view'),
                Action::make('viewUser')
                    ->label('User')
                    ->translateLabel()
                    ->color('info')
                    ->url(fn($record) => UserResource::getUrl('view', ['record' => $record->user->id]))
                    ->icon('heroicon-o-user'),
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->headerActions([])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApiRequestLogs::route('/'),
            //            'create' => Pages\CreateApiRequestLog::route('/create'),
            //            'edit' => Pages\EditApiRequestLog::route('/{record}/edit'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Request Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('method')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'GET' => 'info',
                                        'POST' => 'success',
                                        'PUT', 'PATCH' => 'warning',
                                        'DELETE' => 'danger',
                                        default => 'gray',
                                    }),
                                TextEntry::make('path'),
                                TextEntry::make('route_name')
                                    ->label('Route Name'),
                                TextEntry::make('status_code')
                                    ->badge()
                                    ->color(fn(int $state): string => match (true) {
                                        $state >= 200 && $state < 300 => 'success',
                                        $state >= 400 && $state < 500 => 'warning',
                                        $state >= 500 => 'danger',
                                        default => 'gray',
                                    }),
                                TextEntry::make('ip_address')
                                    ->label('IP Address'),
                                TextEntry::make('user_agent')
                                    ->label('User Agent'),
                                TextEntry::make('execution_time')
                                    ->label('Execution Time (ms)')
                                    ->suffix(' ms'),
                                TextEntry::make('created_at')
                                    ->label('Request Time'),
                            ]),
                    ]),

                Section::make('Request Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                JsonInfoList::make('query_params')
                                    ->label('Query Parameters')
                                    ->columnSpanFull()
                                    ->visible(fn($state): bool => !empty($state)),
                                JsonInfoList::make('request_headers')
                                    ->label('Request Headers')
                                    ->columnSpanFull()
                                    ->visible(fn($state): bool => !empty($state)),
                                JsonInfoList::make('request_body')
                                    ->label('Request Body')
                                    ->columnSpanFull()
                                    ->visible(fn($state): bool => !empty($state)),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Response Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                JsonInfoList::make('response_headers')
                                    ->label('Response Headers')
                                    ->columnSpanFull()
                                    ->visible(fn($state): bool => !empty($state)),
                                TextEntry::make('response_content')
                                    ->label('Response Content')
                                    ->columnSpanFull()
                                    ->formatStateUsing(fn($state) => is_string($state) ? $state : json_encode($state, JSON_PRETTY_PRINT))
                                    ->visible(fn($state) => !empty($state))
                                    ->prose(),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Additional Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('error_message')
                                    ->label('Error Message')
                                    ->columnSpanFull()
                                    ->visible(fn($state) => !empty($state))
                                    ->color('danger'),
                                JsonInfoList::make('additional_data')
                                    ->label('Additional Data')
                                    ->columnSpanFull()
                                    ->visible(fn($state): bool => !empty($state)),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
