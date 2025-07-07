<?php

namespace App\Livewire\Components;

use App\Rules\CheckBlacklist;
use BezhanSalleh\FilamentExceptions\FilamentExceptions;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class EmailVerificationAlert extends Component
{
    public function resendVerification(): void
    {
        try {
            // Validate email of user contain blacklist domain or keyword
            $user = auth()->user();

            $validator = Validator::make(
                ['email' => $user->email],
                ['email' => [new CheckBlacklist]]
            );

            if ($validator->fails()) {
                Notification::make()
                    ->title($validator->errors()->first('email'))
                    ->danger()
                    ->send();

                return;
            }

            $user->sendEmailVerificationNotification();

            Notification::make()
                ->title(trans('A new verification link has been sent to your email address.'))
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title(trans('Failed to send verification email. Please try again.'))
                ->danger()
                ->send();
            FilamentExceptions::report($e);
        }
    }

    public function render()
    {
        return view('livewire.components.email-verification-alert');
    }
}
