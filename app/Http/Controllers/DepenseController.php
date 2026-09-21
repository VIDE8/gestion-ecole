<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use App\Models\Paiement;
use Illuminate\Http\Request;

class DepenseController extends Controller
{
    public function index()
    {
        $depenses = Depense::orderBy('date_depense', 'desc')->get();

        $totalRecettes = Paiement::sum('montant_verse');
        $totalDepenses = Depense::sum('montant');
        $solde = $totalRecettes - $totalDepenses;

        return view('depenses.index', compact('depenses', 'totalRecettes', 'totalDepenses', 'solde'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'required|string|max:255',
            'beneficiaire' => 'required|string|max:255',
            'montant' => 'required|numeric|gt:0',
            'date_depense' => 'required|date',
        ]);

        Depense::create([
            'libelle' => $request->libelle,
            'beneficiaire' => $request->beneficiaire,
            'montant' => $request->montant,
            'date_depense' => $request->date_depense,
            'enregistre_par' => auth()->id(),
        ]);

        return redirect()->route('depenses.index')->with('success', 'Dépense enregistrée avec succès !');
    }
}
