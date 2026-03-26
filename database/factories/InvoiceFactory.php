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
        // Weight towards paid/pending so the table looks realistic
        $statuses = ['paid', 'paid', 'paid', 'pending', 'pending', 'expired', 'draft'];
        $currencies = ['USDC', 'USDT', 'BTC', 'ETH', 'SOL'];
        $purposes = ['freelance_payment', 'investment_return', 'trading_profit', 'propfirm_payout', 'digital_service', 'other'];

        $memosByPurpose = [
            'freelance_payment' => ['UI/UX design sprint', 'Backend API development', 'Smart contract audit', 'Technical writing contract', 'Logo & brand identity'],
            'investment_return'  => ['Q1 yield distribution', 'Liquidity pool return', 'Staking rewards payout', 'Fund redemption'],
            'trading_profit'     => ['BTC/USDC swing trade', 'ETH futures settlement', 'Arbitrage profit — Binance/Kraken', 'Altcoin portfolio rebalance'],
            'propfirm_payout'    => ['January challenge payout', 'Phase 2 funded account', 'Consistency bonus', 'Weekly profit split'],
            'digital_service'    => ['SaaS subscription — Pro Plan', 'API access license Q2', 'White-label reseller fee', 'NFT minting service'],
            'other'              => ['Miscellaneous payment', 'Reimbursement', 'Cross-border settlement', 'Donation'],
        ];

        $status  = $this->faker->randomElement($statuses);
        $purpose = $this->faker->randomElement($purposes);

        return [
            'user_id'        => User::factory(),
            'invoice_number' => 'INV-' . $this->faker->unique()->numberBetween(10000, 99999),
            'amount'         => $this->faker->randomFloat(2, 25, 50000),
            'currency'       => $this->faker->randomElement($currencies),
            'status'         => $status,
            'purpose'        => $purpose,
            'memo'           => $this->faker->randomElement($memosByPurpose[$purpose]),
            'payment_link'   => 'pay.noryxon.com/' . $this->faker->unique()->regexify('[A-Za-z0-9]{12}'),
            'customer_email' => $this->faker->randomElement([
                'alex.morgan@stripe.com', 'dev@chainlink.io', 'finance@phantom.app',
                'pay@alchemy.com', 'billing@quicknode.io', 'accounts@moralis.io',
                null, null,
            ]),
            'paid_at'    => $status === 'paid'    ? $this->faker->dateTimeBetween('-90 days', 'now') : null,
            'expires_at' => $status === 'expired' ? $this->faker->dateTimeBetween('-30 days', '-1 day') : now()->addDays(7),
            'created_at' => $this->faker->dateTimeBetween('-90 days', 'now'),
        ];
    }
}
