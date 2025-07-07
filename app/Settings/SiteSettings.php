<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SiteSettings extends Settings
{
    public array $blacklist_sources;

    public ?string $meta_html = null;

    public ?string $telegram_notify_chat_id = null;

    public ?string $telegram_notify_thread_id = null;

    public ?string $payment_bank_name = null;

    public ?string $payment_bank_number = null;

    public array $footer_menus = [];

    public array $header_menus = [];

    public ?string $main_color = 'teal';

    public array $allowed_registration_domains = [];

    public static function group(): string
    {
        return 'site';
    }
}
