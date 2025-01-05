<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Owner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OwnerPickup>
 */
class OwnerPickupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_id' => rand(1, 3), // This will create a new owner if not provided
            'date' => $this->faker->date,
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'currency_id' => rand(1, 3), // This will create a new currency if not provided
            'description' => $this->faker->sentence,
        ];
    }
}
