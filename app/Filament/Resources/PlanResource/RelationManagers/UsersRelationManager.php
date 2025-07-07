<?php

namespace App\Filament\Resources\PlanResource\RelationManagers;

use App\Enums\UserPlanStatus;
use App\Filament\Resources\PaymentTransactionResource;
use App\Filament\Resources\UserResource;
use App\Models\PaymentTransaction;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn (User $record) => "{$record->name} - {$record->email}")
                    ->searchable(['name', 'email'])
                    ->required()
                    ->prefixAction(
                        \Filament\Forms\Components\Actions\Action::make('viewUser')
                            ->icon('heroicon-m-eye') // icon xem
                            ->color('gray')
                            ->requiresConfirmation(false)
                            ->disabled(fn (Get $get): bool => ! $get('user_id')) // Disable nếu chưa chọn user
                            ->modalContent(function (Get $get, \Filament\Infolists\Infolist $infolist) {
                                $user = User::find($get('user_id'));

                                return UserResource::infolist($infolist)
                                    ->record($user)
                                    ->columns(2);
                            })
                    ),
                Select::make('payment_transaction_id')
                    ->label('Payment Info')
                    ->relationship('paymentTransaction', 'code')
                    ->prefixAction(
                        \Filament\Forms\Components\Actions\Action::make('viewTransaction')
                            ->icon('heroicon-m-eye') // icon xem
                            ->color('gray')
                            ->requiresConfirmation(false)
                            ->disabled(fn (Get $get): bool => ! $get('payment_transaction_id')) // Disable nếu chưa chọn
                            ->modalContent(function (Get $get, \Filament\Infolists\Infolist $infolist) {
                                $transaction = PaymentTransaction::find($get('payment_transaction_id'));

                                return PaymentTransactionResource::infolist($infolist)
                                    ->record($transaction)
                                    ->columns(2);
                            })
                    )
                    ->preload(),
                DatePicker::make('started_at')->label('Start Date')->required()->default(now()),
                DatePicker::make('expired_at')->label('Expired Date'),
                ToggleButtons::make('status')
                    ->options(UserPlanStatus::class)
                    ->default(UserPlanStatus::Active)
                    ->inline(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name')
                    ->description(fn ($record) => $record->user->email)
                    ->searchable(['user.name', 'user.email']),
                Tables\Columns\TextColumn::make('started_at'),
                Tables\Columns\TextColumn::make('expired_at'),
                Tables\Columns\TextColumn::make('created_at'),
                Tables\Columns\TextColumn::make('status')->badge(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                //                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('viewUser')
                    ->label('User')
                    ->translateLabel()
                    ->color('info')
                    ->url(fn ($record) => UserResource::getUrl('view', ['record' => $record->user->id]))
                    ->icon('heroicon-o-user'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
