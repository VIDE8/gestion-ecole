<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Eleve;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $query = Note::with('eleve.classe');

        if ($request->has('search') && $request->search != '' && $request->search != '2026-EP-') {
            $search = $request->search;
            $query->whereHas('eleve', function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                    ->orWhere('prenom', 'LIKE', "%{$search}%")
                    ->orWhere('matricule', 'LIKE', "%{$search}%");
            });
        }

        $notes = $query->get();
        $eleves = Eleve::all();

        return view('notes.index', compact('notes', 'eleves'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'valeur' => 'required|numeric|min:0|max:20',
            'matiere' => 'required|string',
            'eleve_id' => 'required|exists:eleves,id',
        ]);

        Note::create($validated);
        return redirect()->back()->with('success', 'Note enregistrée avec succès !');
    }

    // Affiche le formulaire d'édition pour une note spécifique
    public function edit($id)
    {
        $note = Note::with('eleve.classe')->findOrFail($id);
        return view('notes.edit', compact('note'));
    }

    // Applique la modification de la note en base de données
    public function update(Request $request, $id)
    {
        $note = Note::findOrFail($id);

        $request->validate([
            'valeur' => 'required|numeric|min:0|max:20',
        ]);

        $note->update([
            'valeur' => $request->valeur,
        ]);

        return redirect()->route('notes.index')->with('success', 'La note a été modifiée avec succès !');
    }
}
