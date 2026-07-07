<?php

namespace Database\Factories;

use App\Models\Classe;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClasseFactory extends Factory
{
    protected $model = Classe::class;

    public function definition(): array
    {
        return [
            'nom_classe' => $this->faker->randomElement(['Groupe A', 'Groupe B', 'Section Titulaires']),
            'niveau' => $this->faker->randomElement(['CP1', 'CP2', 'CE1', 'CE2', 'CM1', 'CM2']),
        ];
    }
}
