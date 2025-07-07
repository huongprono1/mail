<?php

namespace App\Filament\Pages;

use App\Settings\SiteSettings;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Tabs\Tab;
use Riodwanto\FilamentAceEditor\AceEditor;
use Telegram\Bot\Laravel\Facades\Telegram;

class SiteSettingsPage extends SettingsPage
{
    protected static ?string $navigationLabel = 'Site Setting';

    protected static ?string $title = 'Site Setting';

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = SiteSettings::class;

    protected static ?string $navigationGroup = 'Settings';

    protected static bool $shouldRegisterNavigation = true;

    public function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Tabs::make('settings')
                    ->tabs([
                        Tabs\Tab::make('General')
                            ->schema([
                                Select::make('main_color')
                                    ->label('Main Color')
                                    ->options([
                                        'teal' => 'Teal',
                                        'blue' => 'Blue',
                                        'red' => 'Red',
                                        'green' => 'Green',
                                        'yellow' => 'Yellow',
                                        'purple' => 'Purple',
                                        'pink' => 'Pink',
                                    ])
                                    ->default('teal')
                                    ->required(),

                                AceEditor::make('meta_html')->label('Meta HTML')
                                    ->mode('html')
                                    ->darkTheme('monokai')
                                    ->columnSpanFull(),
                                Repeater::make('allowed_registration_domains')
                                    ->simple(
                                        TextInput::make('allowed_registration_domain')
                                            ->label('Domain')
                                            ->placeholder('example.com')
                                            ->required(),
                                    )
                                    ->label('Allowed Registration Domains'),
                            ]),
                        Tabs\Tab::make('Notifications')
                            ->schema([
                                TextInput::make('telegram_notify_chat_id')
                                    ->label('Telegram Notify Chat ID')
                                    ->hintAction(
                                        Action::make('sendTestTelegram')
                                            ->label('Send test')
                                            ->icon('heroicon-o-paper-airplane')
                                            ->action(fn (Set $set, Get $get, $state) => $this->sendTelegramTest($state, $get('telegram_notify_thread_id')))
                                            ->visible(fn (Get $get) => $get('telegram_notify_chat_id'))
                                    ),
                                TextInput::make('telegram_notify_thread_id')
                                    ->label('Telegram Notify Thread ID'),
                            ]),
                        Tabs\Tab::make('Payment')
                            ->schema([
                                TextInput::make('payment_bank_name')
                                    ->label('Bank name code'),
                                TextInput::make('payment_bank_number')
                                    ->label('Bank account number'),
                            ]),
                        Tabs\Tab::make('Blacklist Sources')
                            ->schema([
                                Repeater::make('blacklist_sources')
                                    ->label('')
                                    ->schema([
                                        TextInput::make('name')->label('Tên nguồn')->required(),
                                        TextInput::make('url')->label('URL')->required()->url(),
                                        Toggle::make('enabled')->label('Kích hoạt')->default(true),
                                    ])
                                    ->grid(1)
                                    ->collapsible()
                                    ->columns(3)
                                    ->required(),
                            ]),
                        Tabs\Tab::make('Site Menus')
                            ->schema([
                                Repeater::make('header_menus')
                                    ->columns(4)
                                    ->label('Navbar Menus')
                                    ->schema([
                                        TextInput::make('url')->required(),
                                        TextInput::make('label')->required(),
                                        TextInput::make('icon_class'),
                                        Toggle::make('new_tab')
                                            ->label('Open in new tab')
                                            ->inlineLabel()
                                            ->default(false),
                                    ]),
                                Repeater::make('footer_menus')
                                    ->columns(4)
                                    ->label('Footer Menus')
                                    ->schema([
                                        TextInput::make('url')->required(),
                                        TextInput::make('label')->required(),
                                        TextInput::make('icon_class'),
                                        Toggle::make('new_tab')
                                            ->inlineLabel()
                                            ->label('Open in new tab')
                                            ->default(false),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public function sendTelegramTest($chat_id = null, $thread_id = null)
    {
        if ($chat_id) {
            // Call the Telegram sendMessage method
            Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => 'Connected',
                'message_thread_id' => $thread_id,
            ]);

            // Optionally, you can add a success message
            Notification::make()->body('Message sent successfully!')->success()->send();
        }

    }
}
