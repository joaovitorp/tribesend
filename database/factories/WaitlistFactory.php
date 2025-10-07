<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Waitlist>
 */
class WaitlistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'name' => fake()->name(),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'confirmed_at' => fake()->boolean(70) ? fake()->dateTimeBetween('-30 days', 'now') : null,
        ];
    }

    /**
     * Indica que o inscrito está confirmado.
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'confirmed_at' => now(),
        ]);
    }

    /**
     * Indica que o inscrito está pendente de confirmação.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'confirmed_at' => null,
        ]);
    }
}
