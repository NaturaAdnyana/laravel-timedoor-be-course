<?php

namespace Database\Factories;

use App\Models\Category;
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
        return [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'stock' => $this->faker->numberBetween(0, 100),
            'image_url' => "https://karanzi.websites.co.in/obaju-turquoise/img/product-placeholder.png",
            // 'category_id' => Category::inRandomOrder()->first()->id,
            'category_id' => $this->faker->numberBetween(1, 20),
            'brand_id' => $this->faker->numberBetween(1, 20)
        ];
    }
}
