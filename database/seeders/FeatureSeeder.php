<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();   // one timestamp reused for every row

        try {
            DB::table('features')->insert([
                [
                    'id'   => 1,
                    'key'  => 'alias_domains',
                    'name' => json_encode(['vi' => 'Tên miền riêng', 'en' => 'Private domain']),
                    'description' => json_encode(['vi' => 'Tên miền riêng', 'en' => 'Private domain']),
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'id'   => 2,
                    'key'  => 'telegram_notification',
                    'name' => json_encode(['vi' => 'Thông báo qua Telegram', 'en' => 'Notification via Telegram']),
                    'description' => json_encode(['vi' => 'Thông báo qua Telegram', 'en' => 'Notification via Telegram']),
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'id'   => 3,
                    'key'  => 'api-limit',
                    'name' => json_encode(['vi' => 'Lượt gọi API đọc tin nhắn (request/month)', 'en' => 'Limit API read message (req/month)']),
                    'description' => json_encode(['vi' => 'Lượt gọi API đọc tin nhắn (request/month)', 'en' => 'Limit API read message (req/month)']),
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'id'   => 4,
                    'key'  => 'no_ads',
                    'name' => json_encode(['vi' => 'Không quảng cáo', 'en' => 'Remove ads']),
                    'description' => json_encode(['vi' => 'Không quảng cáo', 'en' => 'Remove ads']),
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'id'   => 5,
                    'key'  => 'message_storage_days',
                    'name' => json_encode(['vi' => 'Thời gian lưu thư (ngày)', 'en' => 'Storage message (days)']),
                    'description' => json_encode(['vi' => 'Thời gian lưu thư (ngày)', 'en' => 'Storage message (days)']),
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'id'   => 6,
                    'key'  => 'cloud_sysnc',
                    'name' => json_encode(['vi' => 'Đồng bộ thiết bị', 'en' => 'Sync device account']),
                    'description' => json_encode(['vi' => 'Đồng bộ thiết bị', 'en' => 'Sync device account']),
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'id'   => 7,
                    'key'  => 'atttachment_access',
                    'name' => json_encode(['vi' => 'Xem tệp đính kèm', 'en' => 'Attachment']),
                    'description' => json_encode(['vi' => 'Xem tệp đính kèm', 'en' => 'Attachment']),
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'id'   => 8,
                    'key'  => 'api-throttle',
                    'name' => json_encode(['vi' => 'Rate Limit API (requests/min)', 'en' => 'Rate Limit API (requests/min)']),
                    'description' => json_encode(['vi' => 'Rate Limit API (requests/min)', 'en' => 'Rate Limit API (requests/min)']),
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'id'   => 9,
                    'key'  => 'email-limit',
                    'name' => json_encode(['vi' => 'Số email tối đa', 'en' => 'Email ownership limit']),
                    'description' => json_encode(['vi' => 'Số email tối đa', 'en' => 'Email ownership limit']),
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'id'   => 10,
                    'key'  => 'auto-get-otp',
                    'name' => json_encode(['vi' => 'Tự động lấy OTP trong thư', 'en' => 'Auto get OTP code in message']),
                    'description' => json_encode(['vi' => 'Tự động lấy OTP trong thư', 'en' => 'Auto get OTP code in message']),
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
            ]);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
//            \Log::error('Error seeding features: ' . $e->getMessage());
        }
    }
}
