<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $image = ['products/obat.jpeg', 'products/obat2.jpeg'];

        return [
            'name' => fake()->name,
            'description' => fake()->text(100),
            'price' => fake()->numberBetween(1000, 100000),
            'stock' => fake()->numberBetween(10, 100),
            'image' => $image[rand(0, count($image) - 1)],
            'created_at' => fake()->dateTime,
            'updated_at' => fake()->dateTime
        ];
    }
}
