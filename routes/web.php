<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\EleveApiController;
use Illuminate\Support\Facades\Auth;


Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ROUTE RACINE : Redirige dynamiquement ou affiche la page selon le rôle
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

    // Routes strictes pour les administrateurs
    Route::middleware(['role:admin'])->group(function () {
        Route::post('/classes', [ClasseController::class, 'store'])->name('classes.store');
    });

    // Routes pour les administrateurs et comptables
    Route::middleware(['role:admin,comptable'])->group(function () {
        // Registre des Élèves : servi entièrement par React (voir resources/js/react/EleveApp.jsx)
        Route::get('/eleves', function () {
            return view('eleves.react');
        })->name('eleves.index');

        Route::prefix('api')->group(function () {
            Route::get('/eleves', [EleveApiController::class, 'index']);
            Route::post('/eleves', [EleveApiController::class, 'store']);
            Route::put('/eleves/{id}', [EleveApiController::class, 'update']);
            Route::delete('/eleves/{id}', [EleveApiController::class, 'destroy']);
        });

        // Routes pour la gestion des paiements
        Route::get('/paiements', [PaiementController::class, 'index'])->name('paiements.index');
        Route::post('/paiements', [PaiementController::class, 'store'])->name('paiements.store');

        // NOUVELLES ROUTES : Édition et Mise à jour d'un paiement
        Route::get('/paiements/{id}/edit', [PaiementController::class, 'edit'])->name('paiements.edit');
        Route::put('/paiements/{id}', [PaiementController::class, 'update'])->name('paiements.update');
    });

    // Routes pour les administrateurs et enseignants
    Route::middleware(['role:admin,enseignant'])->group(function () {
        Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');
        Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');

        // NOUVELLES ROUTES : Édition et Mise à jour d'une note
        Route::get('/notes/{id}/edit', [NoteController::class, 'edit'])->name('notes.edit');
        Route::put('/notes/{id}', [NoteController::class, 'update'])->name('notes.update');
    });
});
