<?php

namespace App\Filament\Resources\MailResource\RelationManagers;

use App\Models\Client;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ClientsRelationManager extends RelationManager
{
    protected static string $relationship = 'clients';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //                TextInput::make('subject')
                //                    ->required()
                //                    ->maxLength(255),
                //                Textarea::make('body')->required()->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user_agent')
            ->columns([
                Tables\Columns\TextColumn::make('ip_address')->label('IP')->searchable(),
                Tables\Columns\TextColumn::make('session_id')->label('Session')->limit(20)->searchable(),
                Tables\Columns\TextColumn::make('user_agent')->label('User agent')->limit(20)->searchable(),
                Tables\Columns\TextColumn::make('country'),
                Tables\Columns\TextColumn::make('city'),
                Tables\Columns\TextColumn::make('state'),
                Tables\Columns\TextColumn::make('created_at')->label('Created at'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return Client::getInfolist($infolist);
    }
}
