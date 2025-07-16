<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MailBackendSetting extends Settings
{
    public ?array $servers = [];
    public int $message_expiration_days = 7;
    public int $mail_expiration_minutes = 7;

    public static function group(): string
    {
        return 'mail';
    }
}
