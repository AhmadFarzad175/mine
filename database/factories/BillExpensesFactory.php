<?php

namespace Database\Factories;

use App\Models\ExpenseCategory;
use App\Models\Suppliers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BillExpenses>
 */
class BillExpensesFactory extends Factory
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
            'ref' => 'EXP_' . $this->faker->unique()->numberBetween(1000, 9999),
            'user_id' => rand(1, 3),
            'supplier_id' => rand(1, 3),
            'amount' => $this->faker->randomFloat(2, 100, 10000),
            'currency_id' => rand(1, 3),
            'paid' => $this->faker->randomFloat(2, 50, 5000),
            'Due' => $this->faker->randomFloat(2, 0, 5000),
            'expense_category_id' => rand(1, 3),
            'bill_number' => $this->faker->unique()->numerify('BILL-#####'),
            // 'total_amount' => $this->faker->randomFloat(2, 1000, 15000),
            'description' => $this->faker->sentence(),
        ];
    }
}
