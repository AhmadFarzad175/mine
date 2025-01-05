<?php

namespace Database\Factories;

use App\Models\Customers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\saless>
 */
class SalesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => $this->faker->date(),
            'user_id' => rand(1, 3),
            'customer_id' => rand(1, 3),
            'total_amount' => $this->faker->randomFloat(2, 100, 1000),
            'paid' => $this->faker->randomFloat(2, 50, 500),
            'currency_id' => 1,
            // 'remaining_balance' => $this->faker->randomFloat(2, 50, 500),
            'discount' => $this->faker->randomFloat(2, 50,500),
        ];
    }
}
