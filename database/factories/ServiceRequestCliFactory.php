<?php

namespace Database\Factories;

use App\Models\ServiceRequestCli;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service_request_cli>
 */
class ServiceRequestCliFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ServiceRequestCli::class;

    public function definition()
    {
        return [
            'state' => 5,
            'date_issue' => $this->faker->dateTimeBetween('-2 week', '-1 week'),

            'device' => $this->faker->word(),
            'model' => $this->faker->word(),
            'brand' => $this->faker->word(),
            'serie' => $this->faker->regexify('[A-Z]{5}[0-9]{4}'),
            'description_problem' => $this->faker->text(255),
        ];
    }
}
