<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use Illuminate\Http\Request;

class ScolariteController extends Controller
{
    public function index()
    {
        $classes = Classe::orderBy('niveau')->orderBy('nom_classe')->get();

        return view('scolarite.index', compact('classes'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'montants' => 'required|array',
            'montants.*' => 'nullable|numeric|min:0|max:99999999',
        ]);

        foreach ($validated['montants'] as $classeId => $montant) {
            Classe::where('id', $classeId)->update([
                'scolarite' => ($montant === null || $montant === '') ? null : $montant,
            ]);
        }

        return redirect()->route('scolarite.index')->with('success', 'Frais de scolarité enregistrés.');
    }
}
