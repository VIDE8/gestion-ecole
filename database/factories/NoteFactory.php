<?php

namespace Database\Factories;

use App\Models\Note;
use App\Models\Eleve;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    protected $model = Note::class;

    public function definition(): array
    {
        return [
            'eleve_id' => Eleve::inRandomOrder()->first()->id ?? 1,
            'matiere' => $this->faker->randomElement([
                'Calcul écrit',
                'Calcul mental',
                'Dictée',
                'Étude de texte',
                'Histoire-Géo',
                'EDHC'
            ]),
            'valeur' => $this->faker->randomFloat(2, 6, 19),
        ];
    }
}
