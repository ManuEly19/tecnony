<?php

namespace Database\Factories;

use App\Models\AffiliationAd;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Affiliation_ad>
 */
class AffiliationAdFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = AffiliationAd::class;
    public function definition()
    {
        return [
            'state' => '2',
            'date_acceptance' => $this->faker->date('-1 week'),

            'observation' => $this->faker->paragraph(),
        ];
    }
}
