<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        try {
            $this->migrator->add('ads.global_ads', '');
        } catch (\Exception $exception) {
        }
    }

    public function down(): void
    {
        try {
            $this->migrator->delete('ads.global_ads');
        } catch (\Exception $exception) {
        }
    }
};
