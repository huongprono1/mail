<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PlanRelationManager extends RelationManager
{
    protected static string $relationship = 'plans';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('domain_id')
                    ->relationship('domain', 'name')
                    ->preload()
                    ->required()
                    ->live(),
                TextInput::make('username')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $domain = \App\Models\Domain::find($get('domain_id'));
                        if ($domain && $state) {
                            $set('email', $state.'@'.$domain->name);
                        }
                    }),
                TextInput::make('email')
                    ->required()
                    ->disabled()
                    ->dehydrated()
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('plan.name')
                    ->searchable(),
                TextColumn::make('payment_transaction_id')->label('Transaction')->searchable(),
                TextColumn::make('started_at'),
                TextColumn::make('expired_at'),
                TextColumn::make('status'),
            ])
            ->headerActions([
                CreateAction::make(),
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
}
