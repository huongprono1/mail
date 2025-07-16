<?php

namespace App\Filament\Pages;

use App\Settings\MailBackendSetting;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class MailBackendSettingPage extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Mail Backend Setting';

    protected static ?string $title = 'Mail Backend Setting';

    protected static ?string $navigationGroup = 'Settings';

    protected static bool $shouldRegisterNavigation = true;

    protected static string $settings = MailBackendSetting::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Mail Servers')
                    ->default('Listing mail server domain.')
                    ->schema([
                        Repeater::make('servers')
                            ->label('Domain')
                            ->simple(
                                TextInput::make('domain')->label('Domain')->required(),
                            ),
                    ]),
                Section::make('Message settings')
                    ->default('Time expires for messages in the inbox.')
                    ->schema([
                        TextInput::make('message_expiration_days')
                            ->label('Message Expire (Minutes)')
                            ->numeric()
                            ->default(7)
                            ->required()
                            ->minValue(1)
                            ->maxValue(3600)
                            ->helperText('Messages will be deleted after this many minutes.'),
                        TextInput::make('mail_expiration_minutes')
                            ->label('Mail Expire (Minutes)')
                            ->numeric()
                            ->default(7)
                            ->required()
                            ->minValue(1)
                            ->maxValue(3600)
                            ->helperText('Mail will be deleted after this many minutes.'),
                    ]),
            ]);
    }
}
