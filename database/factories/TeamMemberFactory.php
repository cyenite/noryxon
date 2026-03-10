<?php

namespace Database\Factories;

use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamMemberFactory extends Factory
{
    protected $model = TeamMember::class;

    public function definition(): array
    {
        $name = $this->faker->name;
        $initials = collect(explode(' ', $name))
            ->map(fn($part) => strtoupper(substr($part, 0, 1)))
            ->take(2)
            ->join('');

        return [
            'user_id' => User::factory(),
            'name' => $name,
            'email' => $this->faker->unique()->safeEmail,
            'role' => $this->faker->randomElement(['Admin', 'Developer', 'Analyst', 'Support']),
            'avatar' => $initials,
            'last_active_at' => $this->faker->dateTimeBetween('-5 days', 'now'),
        ];
    }
}
