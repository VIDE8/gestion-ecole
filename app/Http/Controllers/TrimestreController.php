<?php

namespace App\Http\Controllers;

use App\Models\Trimestre;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;

class TrimestreController extends Controller
{
    public function index()
    {
        $trimestres = Trimestre::with('anneeScolaire')->orderByDesc('date_debut')->get();
        $anneesScolaires = AnneeScolaire::orderByDesc('date_debut')->get();

        return view('trimestres.index', compact('trimestres', 'anneesScolaires'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'annee_scolaire_id' => 'required|exists:annees_scolaires,id',
            'numero' => 'required|integer|min:1|max:3',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        Trimestre::create($validated);

        return redirect()->back()->with('success', 'Trimestre créé avec succès !');
    }
}
