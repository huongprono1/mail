<?php

namespace App\Filament\App\Pages;

use App\Models\Mail;
use App\Traits\HasMailable;
use CodeWithDennis\SimpleAlert\Components\Forms\SimpleAlert;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;

class Inbox extends Page implements HasForms
{
    use HasMailable, InteractsWithForms;

    //    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static string $view = 'filament.app.pages.inbox';

    protected ?string $heading = '';

    public ?Mail $mail;

    public static function getNavigationLabel(): string
    {
        return __('Inbox');
    }

    public function getTitle(): string|Htmlable
    {
        return __('Inbox');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return (new self)->allMails()->count() > 0;
    }

    #[On('refreshMail')]
    public function loadMail(): void
    {
        $this->mail = (new self)->getCurrentMail();
        if (! $this->mail) {
            Redirect::to(Home::getUrl());
        }
        $this->dispatch('change-mail', ['mail' => $this->mail]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
//                SimpleAlert::make('warning')
//                    ->warning()
//                    ->title(__('You have multiple email accounts and are not logged in. Others may share access with you. Please log in for a more private experience.')),
//                //                ->visible(!auth()->check())
            ]);
    }

    public function mount(): void
    {
        $this->loadMail();
    }
}
