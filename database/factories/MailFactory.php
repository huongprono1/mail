<?php

namespace Database\Factories;

use App\Models\Domain;
use App\Models\Mail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mail>
 */
class MailFactory extends Factory
{
    protected $model = Mail::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->userName.'@tempmail.id.vn',
            'domain_id' => Domain::first()->id,
            'user_id' => User::first()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
