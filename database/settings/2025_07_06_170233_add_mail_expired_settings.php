<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        try {

            $this->migrator->add('mail.message_expiration_days', 7);

        } catch (\Exception $e) {
        }
    }
};
