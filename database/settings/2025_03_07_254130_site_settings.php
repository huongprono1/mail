<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        // Lưu vào settings
        try {
            $this->migrator->add('site.meta_html', '');
        } catch (\Exception $exception) {
        }

    }
};
