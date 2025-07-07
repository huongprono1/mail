<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\MailResource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class MailsRelationManager extends RelationManager
{
    protected static string $relationship = 'mails';

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
        return MailResource::table($table);
    }
}
