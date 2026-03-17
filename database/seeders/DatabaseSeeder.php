<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\ApiKey;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\TeamMember;
use App\Models\User;
use App\Models\WebhookDelivery;
use App\Models\WebhookEndpoint;
use App\Models\Wallet;
use App\Models\Xpub;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo user
        $user = User::factory()->create([
            'name' => 'Noryxon Demo',
            'email' => 'demo@noryxon.com',
            'password' => Hash::make('password'),
            'business_name' => 'Noryxon Labs',
            'webhook_url' => 'https://api.merchant.com/webhooks/noryxon',
        ]);

        // Seed XPUBs (legacy)
        $xpubs = Xpub::factory()->count(8)->create(['user_id' => $user->id]);

        // Seed Wallets (mixed types)
        $wallets = collect();
        $wallets = $wallets->merge(Wallet::factory()->count(2)->exchange()->create(['user_id' => $user->id]));
        $wallets = $wallets->merge(Wallet::factory()->count(3)->software()->create(['user_id' => $user->id]));
        $wallets = $wallets->merge(Wallet::factory()->count(2)->xpub()->create(['user_id' => $user->id]));
        $wallets = $wallets->merge(Wallet::factory()->count(1)->manual()->create(['user_id' => $user->id]));

        // Seed Payments (link to wallets)
        Payment::factory()->count(50)->create([
            'user_id' => $user->id,
            'xpub_id' => fn () => $xpubs->random()->id,
            'wallet_id' => fn () => $wallets->random()->id,
        ]);

        // Seed Invoices
        Invoice::factory()->count(20)->create(['user_id' => $user->id]);

        // Seed API Keys (2 live + 2 test)
        $liveNames = ['Production API', 'Mobile App'];
        $testNames = ['Staging Key', 'Internal Tool'];

        foreach ($liveNames as $name) {
            $key = ApiKey::generateKey(false);
            ApiKey::factory()->create([
                'user_id' => $user->id,
                'name' => $name,
                'key_hash' => Hash::make($key),
                'key_prefix' => substr($key, 0, 12),
                'is_test' => false,
            ]);
        }

        foreach ($testNames as $name) {
            $key = ApiKey::generateKey(true);
            ApiKey::factory()->create([
                'user_id' => $user->id,
                'name' => $name,
                'key_hash' => Hash::make($key),
                'key_prefix' => substr($key, 0, 12),
                'is_test' => true,
            ]);
        }

        // Seed Webhook Endpoints + Deliveries
        $endpoints = WebhookEndpoint::factory()->count(2)->create(['user_id' => $user->id]);
        foreach ($endpoints as $endpoint) {
            WebhookDelivery::factory()->count(15)->create([
                'webhook_endpoint_id' => $endpoint->id,
            ]);
        }

        // Seed Team Members
        $teamData = [
            ['name' => 'Satoshi Nakamoto', 'email' => 'satoshi@noryxon.com', 'role' => 'Admin', 'avatar' => 'SN'],
            ['name' => 'Vitalik Buterin', 'email' => 'vitalik@noryxon.com', 'role' => 'Developer', 'avatar' => 'VB'],
            ['name' => 'Ada Lovelace', 'email' => 'ada@noryxon.com', 'role' => 'Analyst', 'avatar' => 'AL'],
            ['name' => 'Alan Turing', 'email' => 'alan@noryxon.com', 'role' => 'Support', 'avatar' => 'AT'],
        ];
        foreach ($teamData as $member) {
            TeamMember::factory()->create(array_merge($member, ['user_id' => $user->id]));
        }

        // Seed Audit Logs
        AuditLog::factory()->count(10)->create(['user_id' => $user->id]);
    }
}
