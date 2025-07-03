<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Pet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    protected $model = Pet::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName(),
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'photoUrls' => [$this->faker->imageUrl()],
            'status' => $this->faker->randomElement(OrderStatus::toArray()),
        ];
    }
}
