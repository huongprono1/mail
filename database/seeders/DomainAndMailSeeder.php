<?php

namespace Database\Seeders;

use App\Models\Domain;
use App\Models\Mail;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DomainAndMailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert data into domains table
        try {
            $domain = Domain::firstOrCreate(['name' => 'tempmail.id.vn', 'created_at' => now(), 'updated_at' => now()]);
            Mail::insert(['email' => 'test@tempmail.id.vn', 'domain_id' => $domain->id, 'created_at' => now(), 'updated_at' => now()]);

        } catch (\Exception $exception) {

        }

        try {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@tempmail.test',
                'email_verified_at' => now(),
                'password' => Hash::make('123456'),
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'remember_token' => Str::random(10),
                'profile_photo_path' => null,
                'current_team_id' => null,
            ]);
        } catch (\Exception $exception) {
        }

        // Create 10 mails with 5 messages each
        Mail::factory(10)->create()->each(function ($mail) {
            Message::factory(20)->create([
                'email_id' => $mail->id, // Assign messages to the mail
            ]);
        });
    }
}
