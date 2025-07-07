<?php

namespace App\Filament\Resources\MailResource\RelationManagers;

use App\Filament\Resources\MessageResource;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class MessagesRelationManager extends RelationManager
{
    protected static string $relationship = 'messages';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('subject')
                    ->required()
                    ->maxLength(255),
                Textarea::make('body')->required()->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return MessageResource::table($table);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return MessageResource::infolist($infolist);
    }
}
