<?php

namespace Database\Factories;

use App\Models\Mail;
use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email_id' => Mail::factory(), // Automatically create a related Mail record
            'slug' => $this->faker->unique()->slug(),
            'sender_name' => $this->faker->name(),
            'from' => $this->faker->email,
            'to' => $this->faker->email,
            'subject' => $this->faker->sentence,
            'body' => $this->faker->paragraphs(10, true), // Multi-line fake email content
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'updated_at' => now(),
        ];
    }
}
