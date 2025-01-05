<?php

namespace Database\Factories;

use App\Models\sale;
use App\Models\saleProducts;
use App\Models\sales;
use App\Models\salesProducts;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\salesDetails>
 */
class saleDetailsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sales_id' => rand(1, 3),
            'sale_products_id' => rand(1, 3),
            'quantity' => $this->faker->numberBetween(1, 100),
            'unit_price' => $this->faker->randomFloat(2, 10, 100),
            'total' => function (array $attributes) {
                return $attributes['quantity'] * $attributes['unit_price'];
            },
        ];
        
    }
}
