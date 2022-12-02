<?php

namespace Database\Factories;

use App\Models\SatisfactionForm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Satisfaction_form>
 */
class SatisfactionFormFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = SatisfactionForm::class;

    public function definition()
    {
        return [
            'comment' => $this->faker->paragraph(3),
            'suggestion' => $this->faker->paragraph(2),
            'qualification' => $this->faker->randomFloat(2, 0, 10),
        ];
    }
}
