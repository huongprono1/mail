<?php

namespace App\Filament\App\Pages;

use AbanoubNassem\FilamentGRecaptchaField\Forms\Components\GRecaptcha;
use App\Rules\AllowedRegistrationDomain;
use App\Rules\CheckBlacklist;
use Exception;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;

class Register extends \Filament\Pages\Auth\Register
{
    public $captcha = ''; // must be initialized

    /**
     * @return array<int | string, string | Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        $this->getCaptchaFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getCaptchaFormComponent(): ?GRecaptcha
    {
        return GRecaptcha::make('captcha');
    }

    protected function getEmailFormComponent(): TextInput
    {
        return TextInput::make('email')
            ->label(__('filament-panels::pages/auth/register.form.email.label'))
            ->maxLength(255)
            ->unique($this->getUserModel())
            ->required()
            ->rules([
                'email:rfc,dns,spoof', // RFC 5322 compliant email validation
                new CheckBlacklist, // Check against blacklisted domains and keywords
                new AllowedRegistrationDomain,
            ]);
    }

    /**
     * @throws Exception
     */
    protected function sendEmailVerificationNotification(Model $user): void
    {
        if (! $user instanceof MustVerifyEmail) {
            return;
        }

        if ($user->hasVerifiedEmail()) {
            return;
        }

        if (! method_exists($user, 'notify')) {
            $userClass = $user::class;

            throw new Exception("Model [{$userClass}] does not have a [notify()] method.");
        }

        $user->sendEmailVerificationNotification();
    }
}
