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
use App\Models\Classe;
use App\Models\User;
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
        Route::get('/classes/{classe}/edit', [ClasseController::class, 'edit'])->name('classes.edit');
        Route::put('/classes/{classe}', [ClasseController::class, 'update'])->name('classes.update');

        Route::get('/annees-scolaires', [AnneeScolaireController::class, 'index'])->name('annees_scolaires.index');
        Route::post('/annees-scolaires', [AnneeScolaireController::class, 'store'])->name('annees_scolaires.store');
        Route::post('/annees-scolaires/{id}/activer', [AnneeScolaireController::class, 'activer'])->name('annees_scolaires.activer');

        Route::get('/trimestres', [TrimestreController::class, 'index'])->name('trimestres.index');
        Route::post('/trimestres', [TrimestreController::class, 'store'])->name('trimestres.store');

        Route::get('/comportements', [ComportementController::class, 'indexGlobal'])->name('comportements.index_global');
        Route::post('/comportements', [ComportementController::class, 'storeGlobal'])->name('comportements.store_global');

        // Directeur : choisir une classe puis voir ses statistiques de présence
        Route::get('/statistiques', [PresenceController::class, 'choisirClasse'])->name('presences.statistiques.index');
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
    });

    Route::middleware(['role:comptable'])->group(function () {
        Route::get('/paiements', [PaiementController::class, 'index'])->name('paiements.index');
        Route::post('/paiements', [PaiementController::class, 'store'])->name('paiements.store');

        Route::get('/paiements/{id}/edit', [PaiementController::class, 'edit'])->name('paiements.edit');
        Route::put('/paiements/{id}', [PaiementController::class, 'update'])->name('paiements.update');
    });

    Route::middleware(['role:enseignant'])->group(function () {
        Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');
        Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');

        Route::get('/notes/{id}/edit', [NoteController::class, 'edit'])->name('notes.edit');
        Route::put('/notes/{id}', [NoteController::class, 'update'])->name('notes.update');

        // Retrouve automatiquement la classe dont l'enseignant est titulaire
        Route::get('/ma-classe/appel', function () {
            $classe = Classe::where('enseignant_id', auth()->id())->firstOrFail();
            return redirect()->route('presences.appel', $classe);
        })->name('presences.ma_classe');

        Route::get('/ma-classe/comportements', function () {
            $classe = Classe::where('enseignant_id', auth()->id())->firstOrFail();
            return redirect()->route('comportements.index', $classe);
        })->name('comportements.ma_classe');

        Route::get('/ma-classe/statistiques', function () {
            $classe = Classe::where('enseignant_id', auth()->id())->firstOrFail();
            return redirect()->route('presences.statistiques', $classe);
        })->name('presences.statistiques.ma_classe');
    });

    Route::middleware(['role:admin,enseignant'])->group(function () {
        Route::get('/classes/{classe}/appel', [PresenceController::class, 'appel'])->name('presences.appel');
        Route::post('/classes/{classe}/appel', [PresenceController::class, 'enregistrer'])->name('presences.enregistrer');

        Route::get('/classes/{classe}/comportements', [ComportementController::class, 'index'])->name('comportements.index');
        Route::post('/classes/{classe}/comportements', [ComportementController::class, 'store'])->name('comportements.store');

        Route::get('/classes/{classe}/statistiques', [PresenceController::class, 'statistiquesClasse'])->name('presences.statistiques');
    });
});
