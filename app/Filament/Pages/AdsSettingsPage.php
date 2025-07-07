<?php

namespace App\Filament\Pages;

use App\Settings\AdsSettings;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Riodwanto\FilamentAceEditor\AceEditor;

class AdsSettingsPage extends SettingsPage
{
    protected static ?string $navigationLabel = 'Ads Setting';

    protected static ?string $title = 'Ads Setting';

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static string $settings = AdsSettings::class;

    protected static ?string $navigationGroup = 'Settings';

    protected static bool $shouldRegisterNavigation = true;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('ads_txt')
                    ->label('Content of /ads.txt file')
                    ->hint(fn() => new HtmlString(Blade::render('<x-filament::link href="' . url('/ads.txt') . '">Preview</x-filament::link>'))),

                AceEditor::make('global_ads')
                    ->label('Insert in <head>')
                    ->mode('html')
                    ->columnSpanFull(),
                AceEditor::make('below_form_header')
                    ->label('Before header form')
                    ->mode('html')
                    ->columnSpanFull(),
                AceEditor::make('before_message_body')
                    ->label('Before message body')
                    ->mode('html')
                    ->columnSpanFull(),
                AceEditor::make('after_form_header')
                    ->label('After form header')
                    ->mode('html')
                    ->columnSpanFull(),
                AceEditor::make('header_message')
                    ->label('Header of message')
                    ->mode('html')
                    ->columnSpanFull(),
                AceEditor::make('before_page_content')
                    ->label('Before page content body')
                    ->mode('html')
                    ->columnSpanFull(),

            ])
            ->columns(1);
    }
}
