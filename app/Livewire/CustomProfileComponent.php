<?php

namespace App\Livewire;

use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Laravel\Facades\Telegram;

class CustomProfileComponent extends MyProfileComponent
{
    protected string $view = 'livewire.custom-profile-component';

    public array $only = ['telegram_id'];

    public array $data;

    public $user;

    public $userClass;

    public function mount()
    {
        $this->user = Filament::auth()->user();
        if (!$this->user) {
            abort(403);
        } else {
            $this->userClass = get_class($this->user);
            $this->form->fill($this->user->only($this->only));
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('telegram_id')
                    ->unique()
                    ->label('Telegram ID')
                    ->hint(new HtmlString(__('Chat <code>/myid</code> with :bot to get ID', ['bot' => '<a href="https://t.me/tempmail_id_vn_bot" target="_blank">this Bot</a>'])))
                    ->hintIcon('heroicon-o-question-mark-circle')
                    ->required(),
            ])
            ->statePath('data');
    }

    /**
     * @throws TelegramSDKException
     */
    public function submit(): void
    {
        $data = collect($this->form->getState())->only($this->only)->all();
        $this->user->update($data);

        // Send message to Telegram
        Telegram::sendMessage([
            'chat_id' => $this->user->telegram_id,
            'text' => 'Congratulations! Your Chat ID has been updated successfully.',
        ]);

        Notification::make()
            ->success()
            ->title(__('Profile updated successfully!'))
            ->send();
    }
}
