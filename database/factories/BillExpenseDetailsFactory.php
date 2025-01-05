<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BillExpenseDetails>
 */
class BillExpenseDetailsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bill_expenses_id' =>rand(1,5), // Generates related BillExpense data
            'expense_product_id' =>rand(1,5), // Generates related ExpenseProduct data
            'quantity' => $this->faker->numberBetween(1, 100), // Random quantity between 1 and 100
            'unit_price' => $this->faker->randomFloat(2, 1, 1000), // Random price between 1 and 1000
            'currency_id' =>rand(1,5), // Generates related Currency data
            'total' => 1000, // Calculates total
        ];
    }
}
