<?php

namespace App\Filament\Resources\PaymentTransactionResource\RelationManagers;

use App\Filament\Resources\UserResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class UserPlanRelationManager extends RelationManager
{
    protected static string $relationship = 'userPlan';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
//            ->query($this->getRelationshipQuery())
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('plan.name'),
                Tables\Columns\TextColumn::make('user.name')
                    ->description(fn ($record) => $record->user->email),
                Tables\Columns\TextColumn::make('started_at'),
                Tables\Columns\TextColumn::make('expired_at'),
                Tables\Columns\TextColumn::make('created_at')
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('viewUser')
                    ->label('User')
                    ->color('info')
                    ->url(fn ($record) => UserResource::getUrl('view', ['record' => $record->user_id]))
                    ->icon('heroicon-o-user'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
