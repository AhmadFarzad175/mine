<?php

namespace Database\Factories;

use App\Models\sales;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\salesPayments>
 */
class SalePaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sales_id' =>rand(1, 3),
            'date' => $this->faker->date(),
            'amount' => $this->faker->randomFloat(2, 50, 500),
            'currency_id' => rand(1,3),
            'description' => $this->faker->sentence(),
        ];
    }
}
