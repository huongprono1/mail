<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Client;
use App\Models\Mail;
use App\Models\Message;
use App\Models\Post;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        app(DomainAndMailSeeder::class)->run();
        app(FeatureSeeder::class)->run();
        app(PlanSeeder::class)->run();
        Client::factory(10)->create();
    }
}
