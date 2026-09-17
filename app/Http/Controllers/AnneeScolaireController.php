<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use Illuminate\Http\Request;

class AnneeScolaireController extends Controller
{
    public function index()
    {
        $anneesScolaires = AnneeScolaire::orderByDesc('date_debut')->get();
        return view('annees_scolaires.index', compact('anneesScolaires'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        // Une seule année active à la fois : si on en crée une, on désactive les autres
        if ($request->has('active')) {
            AnneeScolaire::query()->update(['active' => false]);
            $validated['active'] = true;
        }

        AnneeScolaire::create($validated);

        return redirect()->back()->with('success', 'Année scolaire créée avec succès !');
    }

    public function activer($id)
    {
        AnneeScolaire::query()->update(['active' => false]);
        AnneeScolaire::findOrFail($id)->update(['active' => true]);

        return redirect()->back()->with('success', 'Année scolaire activée avec succès !');
    }
}
