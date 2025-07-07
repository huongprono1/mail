<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        try {
            $this->migrator->add('ads.below_form_header', '');
            $this->migrator->add('ads.before_message_body', '');
            $this->migrator->add('ads.after_message_body', '');
            $this->migrator->add('ads.header_message', '');
            $this->migrator->add('ads.before_page_content', '');
            $this->migrator->add('ads.ads_txt', '');
        } catch (\Exception $exception) {
        }
    }

    public function down(): void
    {
        try {
            $this->migrator->delete('ads.below_form_header');
            $this->migrator->delete('ads.before_message_body');
            $this->migrator->delete('ads.after_message_body');
            $this->migrator->delete('ads.header_message');
            $this->migrator->delete('ads.before_page_content');
            $this->migrator->delete('ads.ads_txt');
        } catch (\Exception $exception) {
        }
    }
};
