<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        // Lưu vào settings
        try {
            $this->migrator->add('site.payment_bank_name', '');
            $this->migrator->add('site.payment_bank_number', '');
        } catch (\Exception $exception) {
        }

    }
};
