<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmailOtpRegexRuleResource\Pages;
use App\Models\EmailOtpRegexRule;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmailOtpRegexRuleResource extends Resource
{
    protected static ?string $model = EmailOtpRegexRule::class;

    protected static ?string $slug = 'email-otp-regex-rules';

    protected static ?string $navigationIcon = 'heroicon-o-code-bracket-square';

    protected static ?string $navigationLabel = 'Regex OTP Rules';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('sender_domain')
                    ->required(),

                TextInput::make('regex_pattern')
                    ->hint('/<span[^>]*>(\d{6})<\/span>/')
                    ->required(),

                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?EmailOtpRegexRule $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?EmailOtpRegexRule $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sender_domain'),

                TextColumn::make('regex_pattern')
                    ->badge()
                    ->color('gray')
                    ->copyable(),
            ])
            ->defaultPaginationPageOption(25)
            ->filters([
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmailOtpRegexRules::route('/'),
            'create' => Pages\CreateEmailOtpRegexRule::route('/create'),
            'edit' => Pages\EditEmailOtpRegexRule::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
