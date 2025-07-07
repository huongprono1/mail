<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {

        //        $settings = app(\App\Settings\SiteSettings::class);

        // Danh sách đầy đủ nguồn từ AdGuard & Steven Black
        $sources = [
            // === AdGuard Filters ===
            ['name' => 'AdGuard DNS Filter', 'url' => 'https://adguardteam.github.io/AdGuardSDNSFilter/Filters/filter.txt', 'enabled' => true],
            ['name' => 'AdGuard Base Filter', 'url' => 'https://filters.adtidy.org/extension/chromium/filters/2.txt', 'enabled' => true],
            ['name' => 'AdGuard Mobile Ads Filter', 'url' => 'https://filters.adtidy.org/extension/chromium/filters/11.txt', 'enabled' => true],
            ['name' => 'AdGuard Tracking Protection', 'url' => 'https://filters.adtidy.org/extension/chromium/filters/3.txt', 'enabled' => true],
            ['name' => 'AdGuard Social Media Filter', 'url' => 'https://filters.adtidy.org/extension/chromium/filters/4.txt', 'enabled' => true],
            ['name' => 'AdGuard Annoyances Filter', 'url' => 'https://filters.adtidy.org/extension/chromium/filters/14.txt', 'enabled' => true],
            ['name' => 'AdGuard URL Tracking', 'url' => 'https://filters.adtidy.org/extension/chromium/filters/17.txt', 'enabled' => true],
            ['name' => 'AdGuard Phishing Protection', 'url' => 'https://filters.adtidy.org/extension/chromium/filters/17.txt', 'enabled' => true],
            ['name' => 'AdGuard Spyware Filter', 'url' => 'https://filters.adtidy.org/extension/chromium/filters/1.txt', 'enabled' => true],
            ['name' => 'AdGuard Russian Filter', 'url' => 'https://filters.adtidy.org/extension/chromium/filters/1_1.txt', 'enabled' => true],
            ['name' => 'AdGuard Chinese Filter', 'url' => 'https://filters.adtidy.org/extension/chromium/filters/224.txt', 'enabled' => true],
            ['name' => 'AdGuard German Filter', 'url' => 'https://filters.adtidy.org/extension/chromium/filters/6.txt', 'enabled' => true],
            ['name' => 'AdGuard French Filter', 'url' => 'https://filters.adtidy.org/extension/chromium/filters/16.txt', 'enabled' => true],
            ['name' => 'AdGuard Japanese Filter', 'url' => 'https://filters.adtidy.org/extension/chromium/filters/215.txt', 'enabled' => true],
            ['name' => 'AdGuard Turkish Filter', 'url' => 'https://filters.adtidy.org/extension/chromium/filters/213.txt', 'enabled' => true],

            // === Steven Black's Hosts ===
            ['name' => 'Steven Black’s Unified Hosts', 'url' => 'https://raw.githubusercontent.com/StevenBlack/hosts/master/hosts', 'enabled' => true],
            ['name' => 'Steven Black’s Gambling', 'url' => 'https://raw.githubusercontent.com/StevenBlack/hosts/master/alternates/gambling/hosts', 'enabled' => true],
            ['name' => 'Steven Black’s Adult Content', 'url' => 'https://raw.githubusercontent.com/StevenBlack/hosts/master/alternates/fakenews-gambling/hosts', 'enabled' => true],
        ];

        // Lưu vào settings
        try {
            $this->migrator->add('site.blacklist_sources', $sources);
        } catch (\Exception $exception) {
        }

    }
};
