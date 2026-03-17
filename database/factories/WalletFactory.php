<?php

namespace Database\Factories;

use App\Models\Wallet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletFactory extends Factory
{
    protected $model = Wallet::class;

    public function definition(): array
    {
        $chains = ['btc', 'eth', 'sol', 'matic', 'avax', 'bnb', 'trx', 'arb', 'op', 'base'];

        return [
            'user_id' => User::factory(),
            'type' => 'manual',
            'provider' => 'custom',
            'chain_id' => $this->faker->randomElement($chains),
            'label' => $this->faker->randomElement(['Main Wallet', 'Business Revenue', 'E-commerce', 'Trading', 'Cold Storage', 'Operations']),
            'status' => $this->faker->randomElement(['connected', 'watching', 'paused']),
            'addresses_generated' => 0,
            'total_received' => $this->faker->randomFloat(8, 0.1, 250000),
            'last_synced_at' => $this->faker->dateTimeBetween('-7 days', 'now'),
        ];
    }

    // ─── Type States ───

    public function exchange(): static
    {
        $providers = ['binance', 'coinbase', 'kraken', 'kucoin', 'bybit', 'okx'];
        $provider = $this->faker->randomElement($providers);

        return $this->state(fn () => [
            'type' => 'exchange',
            'provider' => $provider,
            'chain_id' => null, // multi-chain
            'label' => ucfirst($provider) . ' Account',
            'status' => $this->faker->randomElement(['connected', 'syncing', 'paused']),
            'api_key_encrypted' => 'demo_' . $this->faker->regexify('[A-Za-z0-9]{32}'),
            'api_secret_encrypted' => 'demo_' . $this->faker->regexify('[A-Za-z0-9]{64}'),
            'api_passphrase_encrypted' => in_array($provider, ['coinbase', 'kucoin', 'okx']) ? 'demo_pass' : null,
            'address' => null,
            'xpub_key' => null,
            'derivation_path' => null,
        ]);
    }

    public function software(): static
    {
        $wallets = [
            ['provider' => 'metamask', 'chains' => ['eth', 'bnb', 'matic', 'arb', 'op', 'base', 'avax']],
            ['provider' => 'trustwallet', 'chains' => ['eth', 'bnb', 'sol', 'trx', 'matic', 'btc']],
            ['provider' => 'phantom', 'chains' => ['sol', 'eth', 'matic']],
            ['provider' => 'rabby', 'chains' => ['eth', 'bnb', 'matic', 'arb', 'op', 'base']],
            ['provider' => 'rainbow', 'chains' => ['eth', 'arb', 'op', 'base', 'matic']],
        ];
        $wallet = $this->faker->randomElement($wallets);

        return $this->state(fn () => [
            'type' => 'software',
            'provider' => $wallet['provider'],
            'chain_id' => $this->faker->randomElement($wallet['chains']),
            'label' => ucfirst($wallet['provider']) . ' Wallet',
            'status' => $this->faker->randomElement(['connected', 'watching']),
            'address' => '0x' . $this->faker->regexify('[a-f0-9]{40}'),
            'xpub_key' => null,
            'derivation_path' => null,
            'api_key_encrypted' => null,
            'api_secret_encrypted' => null,
            'api_passphrase_encrypted' => null,
        ]);
    }

    public function xpub(): static
    {
        return $this->state(fn () => [
            'type' => 'xpub',
            'provider' => 'custom',
            'chain_id' => $this->faker->randomElement(['btc', 'eth', 'sol']),
            'label' => $this->faker->randomElement(['Ledger Nano', 'Trezor Main', 'Cold Storage', 'Hardware Vault']),
            'status' => $this->faker->randomElement(['connected', 'watching', 'paused']),
            'xpub_key' => 'xpub' . $this->faker->lexify(str_repeat('?', 100)),
            'derivation_path' => "m/84'/0'/0'/0/" . $this->faker->numberBetween(0, 500),
            'addresses_generated' => $this->faker->numberBetween(10, 5000),
            'address' => null,
            'api_key_encrypted' => null,
            'api_secret_encrypted' => null,
            'api_passphrase_encrypted' => null,
        ]);
    }

    public function manual(): static
    {
        return $this->state(fn () => [
            'type' => 'manual',
            'provider' => 'custom',
            'chain_id' => $this->faker->randomElement(['btc', 'eth', 'sol', 'matic', 'bnb']),
            'label' => $this->faker->randomElement(['Prop Firm Payout', 'Freelance Address', 'Trading Wallet', 'Savings']),
            'status' => 'watching',
            'address' => '0x' . $this->faker->regexify('[a-f0-9]{40}'),
            'xpub_key' => null,
            'derivation_path' => null,
            'api_key_encrypted' => null,
            'api_secret_encrypted' => null,
            'api_passphrase_encrypted' => null,
        ]);
    }
}
