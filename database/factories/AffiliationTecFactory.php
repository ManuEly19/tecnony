<?php

namespace Database\Factories;

use App\Models\AffiliationTec;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Affiliation_tec>
 */
class AffiliationTecFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = AffiliationTec::class;

    public function definition()
    {
        return [
            'state' => '2',
            'date_issue' => $this->faker->dateTimeBetween('-3 week', '-2 week'),

            'profession' => $this->faker->sentence(2),
            'specialization' => $this->faker->sentence(2),
            'work_phone' => '09' . $this->faker->randomNumber(8),
            'attention_schedule' => $this->faker->paragraph(2),
            'local_name' => $this->faker->sentence(2),
            'local_address' => $this->faker->paragraph(1),
        ];
    }
}
