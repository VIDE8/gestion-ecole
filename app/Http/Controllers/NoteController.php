<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Eleve;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $estEnseignant = $user->hasRole('enseignant');

        $query = Note::with('eleve.classe');
        $eleveQuery = Eleve::query();

        if ($estEnseignant) {
            $query->whereHas('eleve', function ($q) use ($user) {
                $q->where('classes_id', function ($sub) use ($user) {
                    $sub->select('id')->from('classes')->where('enseignant_id', $user->id);
                });
            });

            $eleveQuery->whereHas('classe', function ($q) use ($user) {
                $q->where('enseignant_id', $user->id);
            });
        }

        if ($request->has('search') && $request->search != '' && $request->search != '2026-EP-') {
            $search = $request->search;
            $query->whereHas('eleve', function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                    ->orWhere('prenom', 'LIKE', "%{$search}%")
                    ->orWhere('matricule', 'LIKE', "%{$search}%");
            });
        }

        $notes = $query->get();
        $eleves = $eleveQuery->get();

        return view('notes.index', compact('notes', 'eleves'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'valeur' => 'required|numeric|min:0|max:20',
            'matiere' => 'required|string',
            'eleve_id' => 'required|exists:eleves,id',
        ]);

        $this->verifierAccesEleve($validated['eleve_id']);

        Note::create($validated);
        return redirect()->back()->with('success', 'Note enregistrée avec succès !');
    }

    // Affiche le formulaire d'édition pour une note spécifique
    public function edit($id)
    {
        $note = Note::with('eleve.classe')->findOrFail($id);
        $this->verifierAccesEleve($note->eleve_id);
        return view('notes.edit', compact('note'));
    }

    // Applique la modification de la note en base de données
    public function update(Request $request, $id)
    {
        $note = Note::findOrFail($id);
        $this->verifierAccesEleve($note->eleve_id);

        $request->validate([
            'valeur' => 'required|numeric|min:0|max:20',
        ]);

        $note->update([
            'valeur' => $request->valeur,
        ]);

        return redirect()->route('notes.index')->with('success', 'La note a été modifiée avec succès !');
    }

    private function verifierAccesEleve($eleveId)
    {
        $user = auth()->user();
        if (!$user->hasRole('enseignant')) {
            return;
        }

        $eleve = Eleve::with('classe')->findOrFail($eleveId);
        if (!$eleve->classe || $eleve->classe->enseignant_id !== $user->id) {
            abort(403, "Vous n'êtes pas autorisé à agir sur cet élève.");
        }
    }
}
