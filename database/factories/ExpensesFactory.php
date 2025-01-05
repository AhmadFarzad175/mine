<?php

namespace Database\Factories;

use App\Models\ExpenseCategory;
use App\Models\ExpenseProducts;
use App\Models\Suppliers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expenses>
 */
class ExpensesFactory extends Factory
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
            'amount' => $this->faker->randomFloat(2, 100, 1000),
            'expense_category_id' =>rand(1,3),
            'currency_id' => rand(1, 3),
            'description' => $this->faker->sentence(),
        ];

    }
}
