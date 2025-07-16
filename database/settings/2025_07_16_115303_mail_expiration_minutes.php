<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        try {

            $this->migrator->add('mail.mail_expiration_minutes', 7);

        } catch (\Exception $e) {
        }
    }
};
