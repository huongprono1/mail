<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        try {
            $this->migrator->add('site.allowed_registration_domains', []);
        } catch (\Exception $exception) {
        }
    }
};
