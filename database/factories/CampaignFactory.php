<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campaign>
 */
class CampaignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'subject' => $this->faker->sentence(5),
            'body_html' => '<h1>'.$this->faker->sentence().'</h1><p>'.$this->faker->paragraph().'</p>',
            'body_text' => $this->faker->paragraph(),
            'status' => 'draft',
            'scheduled_at' => null,
            'sent_at' => null,
            'recipients_count' => 0,
            'sent_count' => 0,
            'opened_count' => 0,
            'clicked_count' => 0,
            'bounced_count' => 0,
            'complained_count' => 0,
        ];
    }

    /**
     * Indicate that the campaign is scheduled.
     */
    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'scheduled',
            'scheduled_at' => now()->addDays(rand(1, 7)),
        ]);
    }

    /**
     * Indicate that the campaign has been sent.
     */
    public function sent(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'sent',
            'sent_at' => now()->subDays(rand(1, 30)),
            'recipients_count' => rand(100, 1000),
            'sent_count' => rand(90, 950),
            'opened_count' => rand(50, 500),
            'clicked_count' => rand(10, 100),
        ]);
    }
}
