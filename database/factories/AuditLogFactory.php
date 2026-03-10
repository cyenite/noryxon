<?php

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition(): array
    {
        $actions = [
            'user.login',
            'user.logout',
            'xpub.created',
            'xpub.deleted',
            'api_key.generated',
            'api_key.revoked',
            'invoice.created',
            'settings.updated',
            'webhook.configured',
            'team_member.invited',
        ];

        return [
            'user_id' => User::factory(),
            'action' => $this->faker->randomElement($actions),
            'ip_address' => $this->faker->ipv4,
            'metadata' => [
                'user_agent' => $this->faker->userAgent,
            ],
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
