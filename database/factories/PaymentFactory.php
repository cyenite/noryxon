<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $chars = 'abcdef0123456789';
        $tokens = ['USDC', 'USDT', 'DAI', 'BTC', 'ETH', 'SOL', 'AVAX', 'MATIC', 'WBTC', 'WETH'];
        $chains = ['btc', 'eth', 'sol', 'matic', 'avax', 'bnb', 'trx', 'arb', 'op', 'base'];
        $statuses = ['confirmed', 'pending', 'processing', 'expired'];

        $status = $this->faker->randomElement($statuses);
        $reqConf = $this->faker->randomElement([3, 6, 12]);

        return [
            'user_id' => User::factory(),
            'xpub_id' => null,
            'wallet_id' => null,
            'tx_hash' => '0x' . $this->faker->unique()->regexify('[a-f0-9]{64}'),
            'amount' => $this->faker->randomFloat(8, 5, 15000),
            'token' => $this->faker->randomElement($tokens),
            'chain_id' => $this->faker->randomElement($chains),
            'status' => $status,
            'confirmations' => match ($status) {
                'confirmed' => $this->faker->numberBetween($reqConf, 50),
                'processing' => $this->faker->numberBetween(1, $reqConf - 1),
                default => 0,
            },
            'required_confirmations' => $reqConf,
            'customer_wallet' => '0x' . $this->faker->regexify('[a-f0-9]{16}') . '...',
            'merchant_wallet' => '0x' . $this->faker->regexify('[a-f0-9]{16}') . '...',
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
