<?php

namespace Database\Factories;

use App\Models\ServiceRequestTec;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service_request_tec>
 */
class ServiceRequestTecFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ServiceRequestTec::class;

    public function definition()
    {
        return [
            'state' => 4,
            'diagnosis' => $this->faker->text(255),

            'incident_resolution' => $this->faker->text(255),
            'warranty' => $this->faker->sentence(5),
            'spare_parts' => $this->faker->sentence(10),
            'price_spare_parts' => $this->faker->randomFloat(2,5,50),
            'final_price' => $this->faker->randomFloat(2,10,70),
            'end_date' => $this->faker->dateTime('now'),

            'observation' => $this->faker->sentence(5),
        ];
    }
}
