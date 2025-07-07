<?php

namespace App\Livewire\TempMail;

use App\Filament\App\Pages\ReadMail;
use App\Models\Mail;
use App\Models\Message;
use App\Traits\HasMailable;
use App\Traits\Toastable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;
use Livewire\Component;

class Inbox extends Component implements HasForms, HasTable
{
    use HasMailable, InteractsWithForms, InteractsWithTable, Toastable;

    public ?Mail $mailbox;

    public function table(Table $table): Table
    {
        return $table
            ->heading(new HtmlString('<span @click="navigator.clipboard.writeText(\''.$this->mailbox?->email.'\')
                  .then(() => $tooltip(\''.__('Copied!').'\', {theme:$store.theme}))
                  .catch(() => $tooltip(\''.__('Copy failed!').'\', {theme:$store.theme}));">'.$this->mailbox?->email ?? __('Inbox').'</span>'))
            ->relationship(fn (): HasMany => $this->mailbox?->messages())
            ->inverseRelationship('messages')
            ->columns([
                TextColumn::make('sender_name')
                    ->label('Sender')
                    ->translateLabel()
                    ->description(fn (Message $record): string => $record->from)
                    ->weight(fn (Message $record): string => ! $record->read_at ? 'bold' : '')
                    ->color(fn (Message $record): string => ! $record->read_at ? 'primary' : '')
//                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject')
                    ->label('Subject')
                    ->translateLabel()
                    ->color(fn (Message $record): string => ! $record->read_at ? 'primary' : '')
                    ->weight(fn (Message $record): string => ! $record->read_at ? 'bold' : ''),
//                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Time')
                    ->translateLabel()
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordUrl(
                fn (Message $record): string => ReadMail::getUrl(['slug' => $record->slug])
            )
            ->filters([

            ])
            ->actions([
            ])
            ->bulkActions([
                // ...
            ])
            ->poll(15)
            ->emptyStateHeading(__('No mail yet!'))
            ->emptyStateDescription(__('Please send mail to your empty email to see here.'))
            ->emptyStateIcon('heroicon-o-envelope-open');
    }

    public function mount(): void
    {
        $this->mailbox = $this->getCurrentMail();
    }

    #[On('change-mail')]
    public function changeMail(?Mail $mail): void
    {
        $this->mailbox = $mail;
    }

    public function render()
    {
        return view('livewire.temp-mail.inbox');
    }
}
