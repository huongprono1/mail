<?php

namespace App\Console\Commands;

use App\Models\Blacklist;
use App\Settings\SiteSettings;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateBlockedDomains extends Command
{
    protected $signature = 'update:blocked-domains';

    protected $description = 'Tải và cập nhật danh sách domain bị chặn';

    private array $sources = [
        'https://raw.githubusercontent.com/StevenBlack/hosts/master/alternates/porn-social-only/hosts',
        'https://raw.githubusercontent.com/StevenBlack/hosts/master/hosts',
        'https://adguardteam.github.io/AdGuardSDNSFilter/Filters/filter.txt',
    ];

    public function handle(): void
    {
        $settings = app(SiteSettings::class);
        $this->line('Đang cập nhật danh sách domain bị chặn...');
        $domains = [];

        foreach ($settings->blacklist_sources as $source) {
            $this->info("Đang tải danh sách từ: {$source['name']} ({$source['url']})");

            try {
                $response = Http::get($source['url']);
                if ($response->ok()) {
                    $domains = array_merge($domains, $this->extractDomains($response->body()));
                } else {
                    $this->error("Không thể tải dữ liệu từ {$source['url']}");
                }
            } catch (\Exception $e) {
                $this->error('Lỗi: '.$e->getMessage());
            }

            $domains = array_unique($domains);
            $this->line('Tổng số domain lấy được: '.count($domains));
            $this->saveToDatabase($domains);
        }
    }

    private function extractDomains(string $content): array
    {
        $lines = explode("\n", $content);
        $domains = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || str_starts_with($line, '#')) {
                continue;
            }

            if (preg_match('/^(?:\d{1,3}\.){3}\d{1,3}\s+([a-zA-Z0-9.-]+)$/', $line, $matches)) {
                $domains[] = $matches[1];
            }

            if (preg_match('/^\|\|([a-zA-Z0-9.-]+)\^/', $line, $matches)) {
                $domains[] = $matches[1];
            }
        }

        return array_filter($domains);
    }

    private function saveToDatabase(array $domains): void
    {
        $this->line('Đang lưu vào database...');
        $batch = [];
        foreach ($domains as $domain) {
            $batch[] = [
                'type' => 'domain',
                'value' => $domain,
                'active' => true,
            ];
            if (count($batch) >= 500) {
                Blacklist::insertOrIgnore($batch);
                $batch = [];
            }
        }

        if (! empty($batch)) {
            Blacklist::insertOrIgnore($batch);
        }

        $this->info('Cập nhật hoàn tất!');
    }
}
