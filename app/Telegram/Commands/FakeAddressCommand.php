<?php

namespace App\Telegram\Commands;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\Str;
use Telegram\Bot\Commands\Command;

class FakeAddressCommand extends Command
{
    protected string $name = 'fake';

    protected string $pattern = '{country}';

    protected string $description = 'Fake address generator';

    protected Generator $faker;

    public function handle()
    {

        $fallbackUsername = $this->getUpdate()->getMessage()->from->username;
        $userId = $this->getUpdate()->getMessage()->from->id;

        $countryCode = $this->argument('country');
        if (empty($countryCode)) {
            $countryCode = 'US';
        }
        $locale = $this->getFakerLocale($countryCode);

        $this->faker = Factory::create($locale);

        $gender = Str::ucfirst($this->faker->randomElement(['male', 'female']));

        $data = [
            'Full Name' => $this->faker->name($gender),
            'Email' => $this->faker->unique()->email(),
            'State' => $this->faker->state(),
            'City' => $this->faker->city(),
            'Street' => $this->faker->streetAddress(),
            'Zip Code' => $this->faker->postcode(),
            'Country' => $this->faker->country(), // Tên quốc gia dựa trên locale
            'Gender' => $gender,
            'Birthday' => $this->faker->date('Y-m-d', '2005-01-01'), // Giới hạn ngày sinh
            'Phone' => $this->faker->phoneNumber(),
        ];

        if (strtoupper($countryCode) === 'US') {
            $data['Social Security Number(SSN)'] = $this->generateFakeUsSsn();
        }

        $text = '';

        foreach ($data as $key => $value) {
            $text .= "*{$key}*: `{$value}`\n";
        }

        $this->replyWithMessage([
            'text' => "✅ Info Generated\n\n{$text}\nRequested by: [$fallbackUsername](tg://user?id=$userId)",
            'parse_mode' => 'Markdown',
        ]);
    }

    protected function getFakerLocale(string $countryCode): string
    {
        return match (strtoupper($countryCode)) {
            'VN' => 'vi_VN',
            'FR' => 'fr_FR',
            'DE' => 'de_DE',
            'JP' => 'ja_JP',
            default => 'en_US',
        };
    }

    protected function generateFakeUsSsn(): string
    {
        // SSN có định dạng XXX-XX-XXXX
        return $this->faker->numerify('###-##-####');
    }
}
