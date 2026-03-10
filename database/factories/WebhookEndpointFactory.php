<?php

namespace Database\Factories;

use App\Models\WebhookEndpoint;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WebhookEndpointFactory extends Factory
{
    protected $model = WebhookEndpoint::class;

    public function definition(): array
    {
        $allEvents = ['payment.confirmed', 'payment.pending', 'payment.expired', 'subscription.renewed', 'subscription.cancelled'];

        return [
            'user_id' => User::factory(),
            'url' => 'https://api.merchant.com/webhooks/noryxon',
            'signing_secret' => WebhookEndpoint::generateSigningSecret(),
            'subscribed_events' => $this->faker->randomElements($allEvents, $this->faker->numberBetween(2, 5)),
            'is_active' => true,
        ];
    }
}
