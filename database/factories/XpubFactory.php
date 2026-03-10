<?php

namespace Database\Factories;

use App\Models\Xpub;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class XpubFactory extends Factory
{
    protected $model = Xpub::class;

    public function definition(): array
    {
        $chains = ['btc', 'eth', 'sol', 'matic', 'avax', 'bnb', 'trx', 'arb', 'op', 'base'];
        $labels = ['Main Wallet', 'Business Revenue', 'E-commerce', 'Lightning Income', 'Cold Storage', 'Operations'];
        $statuses = ['synced', 'watching', 'paused'];
        $chars = 'abcdef0123456789';

        $xpubKey = 'xpub' . $this->faker->lexify(str_repeat('?', 100));

        return [
            'user_id' => User::factory(),
            'chain_id' => $this->faker->randomElement($chains),
            'label' => $this->faker->randomElement($labels),
            'xpub_key' => $xpubKey,
            'derivation_path' => "m/84'/0'/0'/0/" . $this->faker->numberBetween(0, 500),
            'status' => $this->faker->randomElement($statuses),
            'addresses_generated' => $this->faker->numberBetween(10, 5000),
            'total_received' => $this->faker->randomFloat(8, 0.1, 250000),
        ];
    }
}
