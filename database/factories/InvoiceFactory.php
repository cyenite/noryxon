<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $statuses = ['paid', 'pending', 'expired', 'draft'];
        $currencies = ['USDC', 'USDT', 'BTC', 'ETH'];
        $memos = ['Monthly subscription', 'Product purchase', 'Service fee', 'Consulting', 'License renewal', 'API access'];

        $status = $this->faker->randomElement($statuses);

        return [
            'user_id' => User::factory(),
            'invoice_number' => 'INV-' . $this->faker->unique()->numberBetween(1000, 9999),
            'amount' => $this->faker->randomFloat(2, 10, 25000),
            'currency' => $this->faker->randomElement($currencies),
            'status' => $status,
            'memo' => $this->faker->randomElement($memos),
            'payment_link' => 'pay.noryxon.com/' . $this->faker->unique()->regexify('[a-f0-9]{12}'),
            'customer_email' => $this->faker->randomElement(['buyer@example.com', 'client@corp.io', 'user@startup.dev', 'pay@agency.co']),
            'paid_at' => $status === 'paid' ? $this->faker->dateTimeBetween('-3 days', 'now') : null,
            'expires_at' => $status === 'expired' ? $this->faker->dateTimeBetween('-5 days', '-1 day') : null,
            'created_at' => $this->faker->dateTimeBetween('-60 days', 'now'),
        ];
    }
}
