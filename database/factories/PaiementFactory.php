<?php

namespace Database\Factories;

use App\Models\Paiement;
use App\Models\Eleve;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaiementFactory extends Factory
{
    protected $model = Paiement::class;

    public function definition(): array
    {
        return [
            'eleve_id' => Eleve::inRandomOrder()->first()->id ?? 1,
            // Modifié : Génère uniquement des frais de scolarité (écolage)
            'type_frais' => 'scolarite',
            'montant_verse' => $this->faker->randomElement([5000, 10000, 15000, 25000]),
            'date_paiement' => $this->faker->date('Y-m-d', 'now'),
            'reference_recu' => 'REC-2026-' . $this->faker->unique()->numberBetween(100, 999),
        ];
    }
}
