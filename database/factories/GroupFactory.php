<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
 */
class GroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company() . ' Group',
            'description' => fake()->paragraph(3),
            'rules' => [
                [
                    'title' => 'Be respectful',
                    'description' => 'Treat all members with respect and kindness.'
                ],
                [
                    'title' => 'Stay on topic',
                    'description' => 'Keep discussions relevant to the group\'s purpose.'
                ],
                [
                    'title' => 'No spam',
                    'description' => 'Do not post repetitive or promotional content.'
                ]
            ],
            'resources' => [
                [
                    'title' => 'Official Website',
                    'url' => fake()->url()
                ],
                [
                    'title' => 'Documentation',
                    'url' => fake()->url()
                ]
            ],
            'photo' => null, // Will be set manually when needed
            'banner' => null, // Will be set manually when needed
            'owner_id' => User::factory(),
            'member_count' => fake()->numberBetween(1, 500),
            'is_private' => fake()->boolean(20), // 20% chance of being private
        ];
    }

    /**
     * Indicate that the group is private.
     */
    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_private' => true,
        ]);
    }

    /**
     * Indicate that the group is public.
     */
    public function public(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_private' => false,
        ]);
    }

    /**
     * Create a group with a specific owner.
     */
    public function ownedBy(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'owner_id' => $user->id,
        ]);
    }
}
