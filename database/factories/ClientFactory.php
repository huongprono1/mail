<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /**
         * 'session_id' => $sessionId,
         * 'ip_address' => $ip,
         * 'user_agent' => request()->userAgent(),
         * 'country' => $geoip->countryCode,
         * 'city' => $geoip->cityName,
         * 'state' => $geoip->regionName,
         * 'additional_info' => $geoip->toArray(),
         * 'browser' => $agent->browser(),
         * 'device' => $agent->device(),
         * 'platform' => $agent->platform(),
         */
        return [
            'session_id' => $this->faker->unique()->slug,
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'country' => $this->faker->country,
            'city' => $this->faker->city,
            'state' => $this->faker->country,
            'browser' => $this->faker->randomElement(['Chrome', 'Firefox', 'Opera', 'Safari']),
            'device' => $this->faker->randomElement(['desktop', 'tablet']),
            'platform' => $this->faker->randomElement(['android', 'iphone', 'ipad']),
        ];
    }
}
