<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        try {

            $this->migrator->add('site.main_color', 'teal');

        } catch (\Exception $e) {
        }
    }
};
