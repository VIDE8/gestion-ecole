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

    // ROUTE TEMPORAIRE — génère 100 élèves répartis aléatoirement dans les classes. À supprimer après usage.
    Route::get('/generer-eleves-temp', function () {
        $prenomsGarcons = ['Kossi', 'Kodjo', 'Komlan', 'Yao', 'Ayité', 'Edem', 'Mawuli', 'Sena', 'Kwami', 'Fiifi', 'Amevi', 'Dela'];
        $prenomsFilles = ['Ama', 'Akpene', 'Afi', 'Abra', 'Kafui', 'Sedem', 'Elom', 'Mawuena', 'Delali', 'Enam', 'Dede', 'Selom'];
        $noms = ['Agbeko', 'Amouzou', 'Adjei', 'Kponou', 'Tsevi', 'Adzo', 'Bakoubaye', 'Gnassingbé', 'Djidonou', 'Kokou', 'Amegnran', 'Sossou', 'Tchamie', 'Fiawoo', 'Aziaka', 'Dogbe', 'Klutse', 'Ametowobla', 'Toundoh', 'Amewou'];

        $classes = \App\Models\Classe::all();

        $agesParNiveau = [
            'CP1' => [6, 7],
            'CP2' => [7, 8],
            'CE1' => [8, 9],
            'CE2' => [9, 10],
            'CM1' => [10, 11],
            'CM2' => [11, 12],
        ];

        $dernierMatricule = \App\Models\Eleve::where('matricule', 'like', '2026-EP-%')
            ->orderByDesc('matricule')
            ->value('matricule');
        $prochainNumero = $dernierMatricule ? ((int) substr($dernierMatricule, -3)) + 1 : 1;

        $crees = [];

        for ($i = 0; $i < 100; $i++) {
            $classe = $classes->random();
            $estGarcon = rand(0, 1) === 1;
            $prenom = $estGarcon ? $prenomsGarcons[array_rand($prenomsGarcons)] : $prenomsFilles[array_rand($prenomsFilles)];
            $nom = $noms[array_rand($noms)];

            $tranche = $agesParNiveau[$classe->niveau] ?? [7, 10];
            $age = rand($tranche[0], $tranche[1]);
            $dateNaissance = now()->subYears($age)->subDays(rand(0, 364))->format('Y-m-d');

            $matricule = '2026-EP-' . str_pad($prochainNumero, 3, '0', STR_PAD_LEFT);
            $prochainNumero++;

            $eleve = \App\Models\Eleve::create([
                'nom' => $nom,
                'prenom' => $prenom,
                'date_naissance' => $dateNaissance,
                'matricule' => $matricule,
                'classes_id' => $classe->id,
            ]);

            $crees[] = ['matricule' => $eleve->matricule, 'nom' => $eleve->nom, 'prenom' => $eleve->prenom, 'classe' => $classe->nom_classe . ' ' . $classe->niveau];
        }

        return response()->json(['total_crees' => count($crees), 'exemples' => array_slice($crees, 0, 10)]);
    });
});
