<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Service::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'categories' => $this->faker->word(),
            'description' => $this->faker->text(255),
            'price' => $this->faker->randomFloat(2, 5, 50),
        ];
    }
}
