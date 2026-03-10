<?php

namespace Database\Factories;

use App\Models\WebhookDelivery;
use App\Models\WebhookEndpoint;
use Illuminate\Database\Eloquent\Factories\Factory;

class WebhookDeliveryFactory extends Factory
{
    protected $model = WebhookDelivery::class;

    public function definition(): array
    {
        $events = ['payment.confirmed', 'payment.pending', 'payment.expired', 'subscription.renewed', 'subscription.cancelled'];
        $codes = [200, 200, 200, 200, 201, 500, 408, 502];
        $tokens = ['USDC', 'USDT', 'ETH', 'BTC', 'SOL'];
        $chains = ['btc', 'eth', 'sol', 'matic', 'avax'];

        $code = $this->faker->randomElement($codes);
        $success = $code >= 200 && $code < 300;
        $event = $this->faker->randomElement($events);

        return [
            'webhook_endpoint_id' => WebhookEndpoint::factory(),
            'event' => $event,
            'status_code' => $code,
            'success' => $success,
            'response_time_ms' => $this->faker->numberBetween(50, 2500),
            'attempt' => $success ? 1 : $this->faker->numberBetween(1, 5),
            'payload' => [
                'event' => $event,
                'payment_id' => '0x' . $this->faker->regexify('[a-f0-9]{16}'),
                'amount' => $this->faker->randomFloat(2, 10, 5000),
                'currency' => $this->faker->randomElement($tokens),
                'tx_hash' => '0x' . $this->faker->regexify('[a-f0-9]{64}'),
                'status' => 'confirmed',
                'chain' => $this->faker->randomElement($chains),
                'timestamp' => now()->subMinutes(rand(1, 1440))->toISOString(),
            ],
            'signature' => 'sha256=' . $this->faker->regexify('[a-f0-9]{64}'),
            'created_at' => $this->faker->dateTimeBetween('-14 days', 'now'),
        ];
    }
}
