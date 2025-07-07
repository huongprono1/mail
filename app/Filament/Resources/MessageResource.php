<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MessageResource\Pages;
use App\Models\Message;
use App\Services\OtpService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MessageResource extends Resource
{
    //    use LogsActivity;

    protected static ?string $model = Message::class;

    protected static ?string $slug = 'messages';

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';

    //    public static function getNavigationBadge(): ?string
    //    {
    //        return static::getModel()::count();
    //    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('email_id')
                    ->relationship('email', 'email')
                    ->optionsLimit(10)
                    ->required(),
                TextInput::make('sender_name')
                    ->required(),
                TextInput::make('from')->email()
                    ->required(),
                TextInput::make('to')->email()
                    ->required(),
                TextInput::make('subject')
                    ->required(),
                TextInput::make('otp_code')
                    ->required(),
                DatePicker::make('read_at')
                    ->label('Read Date'),
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn (?Message $record): string => $record?->created_at?->diffForHumans() ?? '-'),
                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn (?Message $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
                Textarea::make('body')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sender_name')
                    ->label('Sender')
                    ->translateLabel()
                    ->description(fn (Message $record): string => $record->from)
                    ->searchable()
                    ->copyable(),
                TextColumn::make('subject')
                    ->label('Subject')
                    ->translateLabel()
                    ->html()
                    ->wrap()
                    ->description(fn (Message $record): string => $record->email->email)
                    ->searchable(),
                TextColumn::make('read_at')->label('Seen'),
                TextColumn::make('created_at'),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                Action::make('getOtp')
                    ->label('OTP')
                    ->action(function (Message $record) {
                        $otpService = new OtpService($record);
                        $otpCode = $otpService->getOtpCode();
                        if ($otpCode) {
                            $record->otp_code = $otpCode;
                            if ($record->isDirty()) {
                                $record->save();
                            }
                            Notification::make()->success()->title('OTP Code: '.$otpCode)->send();
                        } else {
                            Notification::make()->danger()->title('No OTP Code found.')->send();
                        }
                    })
                    ->icon('heroicon-s-key'),
                ActionGroup::make([
                    Action::make('viewUser')
                        ->label('User')
                        ->translateLabel()
                        ->color('info')
                        ->url(fn ($record) => UserResource::getUrl('view', ['record' => $record->email->user]))
                        ->icon('heroicon-o-user')
                        ->visible(fn (Message $record): bool => $record->email?->user !== null),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
                    ->button(),
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
            'index' => Pages\ListMessages::route('/'),
            'create' => Pages\CreateMessage::route('/create'),
            'edit' => Pages\EditMessage::route('/{record}/edit'),
            'view' => Pages\ViewMessage::route('/{record}'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return Message::getInfolist($infolist);
    }
}
