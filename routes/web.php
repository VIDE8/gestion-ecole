<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\AnneeScolaireController;
use App\Http\Controllers\TrimestreController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\EleveApiController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\ComportementController;
use Illuminate\Support\Facades\Auth;

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user && $user->hasRole('admin')) {
            return app(ClasseController::class)->index();
        }

        if ($user && $user->hasRole('comptable')) {
            return redirect()->route('eleves.index');
        }

        if ($user && $user->hasRole('enseignant')) {
            return redirect()->route('notes.index');
        }

        abort(403, 'Action non autorisée pour votre profil.');
    })->name('home');

    Route::middleware(['role:admin'])->group(function () {
        Route::post('/classes', [ClasseController::class, 'store'])->name('classes.store');

        Route::get('/annees-scolaires', [AnneeScolaireController::class, 'index'])->name('annees_scolaires.index');
        Route::post('/annees-scolaires', [AnneeScolaireController::class, 'store'])->name('annees_scolaires.store');
        Route::post('/annees-scolaires/{id}/activer', [AnneeScolaireController::class, 'activer'])->name('annees_scolaires.activer');

        Route::get('/trimestres', [TrimestreController::class, 'index'])->name('trimestres.index');
        Route::post('/trimestres', [TrimestreController::class, 'store'])->name('trimestres.store');
    });

    Route::middleware(['role:admin,comptable'])->group(function () {
        Route::get('/eleves', function () {
            return view('eleves.react');
        })->name('eleves.index');

        Route::prefix('api')->group(function () {
            Route::get('/eleves', [EleveApiController::class, 'index']);
            Route::post('/eleves', [EleveApiController::class, 'store']);
            Route::put('/eleves/{id}', [EleveApiController::class, 'update']);
            Route::delete('/eleves/{id}', [EleveApiController::class, 'destroy']);
        });

        Route::get('/paiements', [PaiementController::class, 'index'])->name('paiements.index');
        Route::post('/paiements', [PaiementController::class, 'store'])->name('paiements.store');

        Route::get('/paiements/{id}/edit', [PaiementController::class, 'edit'])->name('paiements.edit');
        Route::put('/paiements/{id}', [PaiementController::class, 'update'])->name('paiements.update');
    });

    Route::middleware(['role:admin,enseignant'])->group(function () {
        Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');
        Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');

        Route::get('/notes/{id}/edit', [NoteController::class, 'edit'])->name('notes.edit');
        Route::put('/notes/{id}', [NoteController::class, 'update'])->name('notes.update');

        Route::get('/classes/{classe}/appel', [PresenceController::class, 'appel'])->name('presences.appel');
        Route::post('/classes/{classe}/appel', [PresenceController::class, 'enregistrer'])->name('presences.enregistrer');

        Route::get('/classes/{classe}/comportements', [ComportementController::class, 'index'])->name('comportements.index');
        Route::post('/classes/{classe}/comportements', [ComportementController::class, 'store'])->name('comportements.store');
    });

    // ROUTE TEMPORAIRE — génère notes/présences/comportements pour 10 élèves existants. À supprimer après usage.
    Route::get('/generer-donnees-temp', function () {
        $eleves = \App\Models\Eleve::with('classe')->inRandomOrder()->take(10)->get();

        $matieres = ['Calcul écrit / Opérations', 'Calcul mental', 'Lecture', 'Dictée', 'Étude'];
        $typesComportement = ['incident', 'appreciation', 'sanction'];
        $descriptions = [
            'incident' => 'Bavardage répété pendant le cours.',
            'appreciation' => 'Très bonne participation en classe.',
            'sanction' => 'Retard non justifié de 30 minutes.',
        ];

        $resultat = ['notes' => 0, 'presences' => 0, 'comportements' => 0];

        foreach ($eleves as $eleve) {
            if (!$eleve->classe) {
                continue;
            }
            $enseignantId = $eleve->classe->enseignant_id;

            // 2 à 3 notes par élève
            foreach (array_rand($matieres, rand(2, 3)) as $index) {
                \App\Models\Note::create([
                    'eleve_id' => $eleve->id,
                    'matiere' => is_array($index) ? $matieres[$index[0]] : $matieres[$index],
                    'valeur' => rand(5, 20),
                ]);
                $resultat['notes']++;
            }

            // Présences sur les 5 derniers jours
            for ($j = 0; $j < 5; $j++) {
                $statut = collect(['present', 'present', 'present', 'absent', 'retard'])->random();
                \App\Models\Presence::updateOrCreate(
                    ['eleve_id' => $eleve->id, 'date' => now()->subDays($j)->toDateString()],
                    [
                        'classe_id' => $eleve->classes_id,
                        'statut' => $statut,
                        'justifie' => false,
                        'enregistre_par' => $enseignantId,
                    ]
                );
                $resultat['presences']++;
            }

            // 1 comportement par élève
            $type = $typesComportement[array_rand($typesComportement)];
            \App\Models\Comportement::create([
                'eleve_id' => $eleve->id,
                'classe_id' => $eleve->classes_id,
                'date' => now()->subDays(rand(0, 4))->toDateString(),
                'type' => $type,
                'description' => $descriptions[$type],
                'enregistre_par' => $enseignantId,
            ]);
            $resultat['comportements']++;
        }

        return response()->json($resultat);
    });
});
