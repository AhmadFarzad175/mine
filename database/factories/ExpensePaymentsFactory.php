<?php

namespace Database\Factories;

use App\Models\ExpensePayments;
use App\Models\Expenses;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExpensePayments>
 */
class ExpensePaymentsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bill_expense_id' => rand(1, 3),
            'date' => $this->faker->date(),
            'amount' => $this->faker->randomFloat(2, 100, 10000),
            'currency_id' => rand(1, 3),
            'description' => $this->faker->sentence(),
        ];
    }
}
