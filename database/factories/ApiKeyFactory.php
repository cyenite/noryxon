<?php

namespace Database\Factories;

use App\Models\ApiKey;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class ApiKeyFactory extends Factory
{
    protected $model = ApiKey::class;

    public function definition(): array
    {
        $isTest = $this->faker->boolean(50);
        $prefix = $isTest ? 'sk_test_' : 'sk_live_';
        $key = $prefix . $this->faker->regexify('[a-f0-9]{32}');
        $names = ['Production API', 'Staging Key', 'Mobile App', 'Shopify Plugin', 'Internal Tool'];

        return [
            'user_id' => User::factory(),
            'name' => $this->faker->randomElement($names),
            'key_hash' => Hash::make($key),
            'key_prefix' => substr($key, 0, 12),
            'is_test' => $isTest,
            'last_used_at' => $this->faker->optional(0.7)->dateTimeBetween('-7 days', 'now'),
            'ip_whitelist' => $this->faker->optional(0.5)->randomElements([
                $this->faker->ipv4,
                $this->faker->ipv4,
            ], rand(1, 2)),
            'requests_today' => $this->faker->numberBetween(0, 5000),
        ];
    }
}
