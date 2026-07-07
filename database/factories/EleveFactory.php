<?php

namespace Database\Factories;

use App\Models\Eleve;
use App\Models\Classe;
use Illuminate\Database\Eloquent\Factories\Factory;

class EleveFactory extends Factory
{
    protected $model = Eleve::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->lastName(),
            'prenom' => $this->faker->firstName(),
            'date_naissance' => $this->faker->date('Y-m-d', '-6 years'),
            'matricule' => '2026-EP-' . $this->faker->unique()->numberBetween(100, 999),
            'classes_id' => Classe::factory(),
        ];
    }
}
