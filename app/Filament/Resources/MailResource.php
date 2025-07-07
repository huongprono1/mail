<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MailResource\Pages;
use App\Filament\Resources\MailResource\RelationManagers\ClientsRelationManager;
use App\Filament\Resources\MailResource\RelationManagers\MessagesRelationManager;
use App\Models\Domain;
use App\Models\Mail;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;
use Rmsramos\Activitylog\RelationManagers\ActivitylogRelationManager;

class MailResource extends Resource
{
    protected static ?string $model = Mail::class;

    protected static ?string $slug = 'mails';

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $recordTitleAttribute = 'email';

    //    public static function getNavigationBadge(): ?string
    //    {
    //        return static::getModel()::count();
    //    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable(['name', 'email']),
                Select::make('domain_id')
                    ->relationship('domain', 'name')
                    ->preload()
                    ->required()
                    ->live(),

                TextInput::make('username')
                    ->required()
                    ->live(onBlur: true)
                    ->visibleOn('create')
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $domain = \App\Models\Domain::find($get('domain_id'));
                        if ($domain && $state) {
                            $set('email', $state.'@'.$domain->name);
                        }
                    }),
                TextInput::make('email')
                    ->required()
                    ->dehydrated(true),
                //                    ->disabled(fn($context) => $context === 'create'),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('email')
                    ->copyable()
                    ->searchable(),
                //                TextColumn::make('domain.name'),
                TextColumn::make('user.name')->placeholder('-'),
                TextColumn::make('messages_count')
                    ->label('Messages')
                    ->counts('messages')
                    ->badge()
                    ->icon('heroicon-o-envelope'),
                TextColumn::make('clients_count')
                    ->label('Clients')
                    ->counts('clients')
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-o-users'),
                TextColumn::make('created_at'),
                TextColumn::make('updated_at')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('domain_id')
                    ->options(Domain::all()->pluck('name', 'id'))
                    ->label('Domain'),
            ])
            ->actions([
                Action::make('viewUser')
                    ->label('User')
                    ->translateLabel()
                    ->color('info')
                    ->url(fn ($record) => UserResource::getUrl('view', ['record' => $record->user]))
                    ->icon('heroicon-o-user')
                    ->visible(fn (Mail $record): bool => $record->user !== null),
                ActivityLogTimelineTableAction::make('Activities'),
                ActionGroup::make([
                    \Filament\Tables\Actions\ViewAction::make()
                        ->url(fn (Mail $record) => MailResource::getUrl('view', ['record' => $record])),
                    EditAction::make(),
                    DeleteAction::make(),
                    RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMails::route('/'),
            'create' => Pages\CreateMail::route('/create'),
            'edit' => Pages\EditMail::route('/{record}/edit'),
            'view' => Pages\ViewMail::route('/{record}'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['email'];
    }

    public static function getRelations(): array
    {
        return [
            MessagesRelationManager::class,
            ClientsRelationManager::class,
            ActivitylogRelationManager::class,
        ];
    }
}
