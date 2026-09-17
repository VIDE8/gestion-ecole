<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Comportement;
use Illuminate\Http\Request;

class ComportementController extends Controller
{
    public function index(Classe $classe)
    {
        $this->authorizeClasse($classe);

        $eleves = $classe->eleves()->orderBy('nom')->get();
        $comportements = Comportement::where('classe_id', $classe->id)
            ->with('eleve')
            ->orderByDesc('date')
            ->get();

        return view('comportements.index', compact('classe', 'eleves', 'comportements'));
    }

    public function store(Request $request, Classe $classe)
    {
        $this->authorizeClasse($classe);

        $data = $request->validate([
            'eleve_id' => 'required|exists:eleves,id',
            'type' => 'required|in:incident,appreciation,sanction',
            'description' => 'required|string|max:1000',
        ]);

        Comportement::create([
            'eleve_id' => $data['eleve_id'],
            'classe_id' => $classe->id,
            'date' => now()->toDateString(),
            'type' => $data['type'],
            'description' => $data['description'],
            'enregistre_par' => auth()->id(),
        ]);

        return back()->with('success', 'Enregistrement ajouté.');
    }

    private function authorizeClasse(Classe $classe)
    {
        if (auth()->user()->hasRole('enseignant') && $classe->enseignant_id !== auth()->id()) {
            abort(403, "Vous n'êtes pas titulaire de cette classe.");
        }
    }
}
