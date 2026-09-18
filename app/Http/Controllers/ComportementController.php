<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Comportement;
use App\Models\Eleve;
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

    /**
     * Vue globale réservée à l'admin : comportement de TOUS les élèves,
     * avec filtre optionnel par classe.
     * GET /comportements
     */
    public function indexGlobal(Request $request)
    {
        $query = Comportement::with(['eleve', 'classe']);

        if ($request->filled('classe_id')) {
            $query->where('classe_id', $request->classe_id);
        }

        $comportements = $query->orderByDesc('date')->get();

        $classes = Classe::orderBy('nom_classe')->get();
        $eleves = Eleve::with('classe')->orderBy('nom')->get();

        return view('comportements.index_global', compact('comportements', 'classes', 'eleves'));
    }

    /**
     * Ajout d'un enregistrement depuis la vue globale admin.
     * La classe est déduite de l'élève sélectionné.
     * POST /comportements
     */
    public function storeGlobal(Request $request)
    {
        $data = $request->validate([
            'eleve_id' => 'required|exists:eleves,id',
            'type' => 'required|in:incident,appreciation,sanction',
            'description' => 'required|string|max:1000',
        ]);

        $eleve = Eleve::findOrFail($data['eleve_id']);

        Comportement::create([
            'eleve_id' => $eleve->id,
            'classe_id' => $eleve->classes_id,
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
