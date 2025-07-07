<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();           // same timestamp for every row

        try {
            DB::table('plans')->insert([
                [
                    'id'          => 1,
                    'key'         => 'basic',
                    'name'        => json_encode(['vi' => 'Basic',   'en' => 'Basic']),
                    'description' => json_encode(['vi' => 'Basic',   'en' => 'Basic']),
                    'price'       => 0.00,
                    'is_active'   => 1,
                    'month_price' => json_encode(['vi' => '0',       'en' => '0']),
                    'year_price'  => json_encode(['vi' => '0',       'en' => '0']),
                    'currency'    => json_encode(['vi' => 'VND',     'en' => 'USD']),
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'id'          => 2,
                    'key'         => 'premium',
                    'name'        => json_encode(['vi' => 'Premium', 'en' => 'Premium']),
                    'description' => json_encode(['vi' => 'Premium', 'en' => 'Premium']),
                    'price'       => 69000.00,
                    'is_active'   => 1,
                    'month_price' => json_encode(['vi' => '69000',   'en' => '3']),
                    'year_price'  => json_encode(['vi' => '690000',  'en' => '30']),
                    'currency'    => json_encode(['vi' => 'VND',     'en' => 'USD']),
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
            ]);
        } catch (\Exception $e) {
            // Handle the exception if needed
            // For example, log the error or display a message
//            \Log::error('Error seeding plans: ' . $e->getMessage());
        }
    }
}
