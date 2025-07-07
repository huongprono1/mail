<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AdsSettings extends Settings
{
    public ?string $below_form_header = null;

    public ?string $before_message_body = null;

    public ?string $after_message_body = null;

    public ?string $header_message = null;

    public ?string $before_page_content = null;

    public ?string $ads_txt = null;

    public ?string $after_form_header = null;

    public ?string $global_ads = null;

    public static function group(): string
    {
        return 'ads';
    }
}
