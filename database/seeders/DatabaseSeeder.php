<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Paiement;
use App\Models\Note;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Directeur',
            'email' => 'admin@ecole.tg',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Enseignant CM1',
            'email' => 'enseignant@ecole.tg',
            'password' => Hash::make('password'),
            'role' => 'enseignant',
        ]);

        User::create([
            'name' => 'Comptable École',
            'email' => 'comptable@ecole.tg',
            'password' => Hash::make('password'),
            'role' => 'comptable',
        ]);

        $niveaux = ['CP1', 'CP2', 'CE1', 'CE2', 'CM1', 'CM2'];
        foreach ($niveaux as $niveau) {
            Classe::create(['nom_classe' => 'Groupe A', 'niveau' => $niveau]);
            Classe::create(['nom_classe' => 'Groupe B', 'niveau' => $niveau]);
        }

        $nomsTogo = ['KOFFI', 'MENSAH', 'AGBEGNENOU', 'ADEDJE', 'AMEDEE', 'SOSSOU', 'GADO', 'ALAZA', 'TCHALIM', 'AYEVA'];
        $prenomsTogo = ['Yao', 'Afi', 'Komi', 'Adjo', 'Kodjo', 'Ekoué', 'Fafa', 'Kossiwa', 'Abdou', 'Amavi'];

        for ($i = 1; $i <= 50; $i++) {
            $nom = $nomsTogo[array_rand($nomsTogo)];
            $prenom = $prenomsTogo[array_rand($prenomsTogo)];
            $classe = Classe::inRandomOrder()->first();

            $anneesScolaires = [
                'CP1' => 2021,
                'CP2' => 2020,
                'CE1' => 2019,
                'CE2' => 2018,
                'CM1' => 2017,
                'CM2' => 2015,
            ];

            $anneeNaissance = $anneesScolaires[$classe->niveau] ?? 2019;
            $dateNaissance = $anneeNaissance . '-' . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . '-' . str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT);

            Eleve::create([
                'nom' => $nom,
                'prenom' => $prenom,
                'date_naissance' => $dateNaissance,
                'matricule' => '2026-EP-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'classes_id' => $classe->id
            ]);
        }

        Note::factory()->count(100)->create();

        Paiement::factory()->count(25)->create();
    }
}
