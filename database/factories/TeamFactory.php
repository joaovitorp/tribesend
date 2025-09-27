<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'description' => fake()->sentence(),
            'category' => fake()->randomElement([
                'AI',
                'Developer',
                'Marketing',
                'Game Development',
                'Journalist',
                'Writer',
                'Travel',
                'E-commerce',
                'Finance',
                'Healthcare',
                'Education',
                'Consulting',
                'Design',
                'Photography',
                'Music',
                'Sports',
                'Food & Beverage',
                'Real Estate',
                'Legal',
                'Non-profit',
                'Other',
            ]),
            'owner_id' => \App\Models\User::factory(),
        ];
    }
}
