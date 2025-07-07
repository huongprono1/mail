<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentTransactionResource\Pages;
use App\Filament\Resources\PaymentTransactionResource\RelationManagers\UserPlanRelationManager;
use App\Models\PaymentTransaction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentTransactionResource extends Resource
{
    protected static ?string $model = PaymentTransaction::class;

    protected static ?string $slug = 'payment-transactions';

    protected static ?string $navigationGroup = 'Pricing';

    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('transaction_number')
                    ->required()
                    ->integer(),

                TextInput::make('gateway')
                    ->required(),

                DateTimePicker::make('transaction_date'),

                TextInput::make('account_number')
                    ->required(),

                TextInput::make('code')
                    ->required(),

                MarkdownEditor::make('content')
                    ->required(),

                TextInput::make('transfer_type')
                    ->required(),

                TextInput::make('amount')
                    ->required()
                    ->numeric(),

                TextInput::make('accumulated')
                    ->required()
                    ->numeric(),

                TextInput::make('sub_account')
                    ->required(),

                TextInput::make('reference_code')
                    ->required(),

                TextInput::make('description'),

                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn (?PaymentTransaction $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn (?PaymentTransaction $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_number'),

                TextColumn::make('gateway')
                    ->description(fn ($record) => $record->account_number),

                TextColumn::make('code'),

                TextColumn::make('amount'),

                TextColumn::make('accumulated'),

                TextColumn::make('reference_code'),

                TextColumn::make('created_at'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                Action::make('viewUser')
                    ->label('User')
                    ->color('info')
                    ->url(fn ($record) => UserResource::getUrl('view', ['record' => $record->userPlan->user_id]))
                    ->icon('heroicon-o-user')
                    ->visible(fn ($record) => $record->userPlan),
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentTransactions::route('/'),
            'create' => Pages\CreatePaymentTransaction::route('/create'),
            'edit' => Pages\EditPaymentTransaction::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('transaction_number')->label('Transaction Number'),
                TextEntry::make('gateway')->label('Gateway'),
                TextEntry::make('transaction_date')->label('Transaction Date')->date(),
                TextEntry::make('account_number')->label('Account Number'),
                TextEntry::make('code')->label('Code'),
                TextEntry::make('content')->label('Content'),
                TextEntry::make('transfer_type')->label('Transfer Type'),
                TextEntry::make('amount')->label('Amount')->money(),
                TextEntry::make('accumulated')->label('Accumulated'),
                TextEntry::make('sub_account')->label('Sub Account'),
                TextEntry::make('reference_code')->label('Reference Code'),
                TextEntry::make('description')->label('Description'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            UserPlanRelationManager::class,
        ];
    }
}
