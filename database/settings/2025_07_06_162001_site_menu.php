<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        try {
            $this->migrator->add('site.footer_menus', []);
            $this->migrator->add('site.header_menus', []);
        } catch (\Exception $exception) {
        }
    }
};
