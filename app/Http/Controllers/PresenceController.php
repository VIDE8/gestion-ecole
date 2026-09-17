<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Presence;
use Illuminate\Http\Request;

class PresenceController extends Controller
{
    public function appel(Classe $classe)
    {
        $this->authorizeClasse($classe);

        $eleves = $classe->eleves()->orderBy('nom')->get();
        $today = now()->toDateString();

        $presencesDuJour = Presence::where('classe_id', $classe->id)
            ->where('date', $today)
            ->pluck('statut', 'eleve_id');

        return view('presences.appel', compact('classe', 'eleves', 'presencesDuJour'));
    }

    public function enregistrer(Request $request, Classe $classe)
    {
        $this->authorizeClasse($classe);

        $data = $request->validate([
            'statuts' => 'required|array',
            'statuts.*' => 'in:present,absent,retard',
        ]);

        foreach ($data['statuts'] as $eleveId => $statut) {
            Presence::updateOrCreate(
                ['eleve_id' => $eleveId, 'date' => now()->toDateString()],
                [
                    'classe_id' => $classe->id,
                    'statut' => $statut,
                    'enregistre_par' => auth()->id(),
                ]
            );
        }

        return back()->with('success', "Appel enregistré.");
    }

    private function authorizeClasse(Classe $classe)
    {
        if (auth()->user()->hasRole('enseignant') && $classe->enseignant_id !== auth()->id()) {
            abort(403, "Vous n'êtes pas titulaire de cette classe.");
        }
    }
}
