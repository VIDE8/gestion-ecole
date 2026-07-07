<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Eleve;
use Illuminate\Http\Request;

/**
 * Contrôleur API (JSON) pour le module Élèves.
 * Consommé par le front-end React (resources/js/react).
 * N'affecte pas les routes/vues Blade existantes (module 100% additif).
 */
class EleveApiController extends Controller
{
    /**
     * Liste des élèves (avec filtres optionnels) + classes + prochain matricule.
     * GET /api/eleves
     */
    public function index(Request $request)
    {
        $query = Eleve::with(['classe', 'paiements']);

        if ($request->filled('classe_id')) {
            $query->where('classes_id', $request->classe_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                    ->orWhere('prenom', 'LIKE', "%{$search}%")
                    ->orWhere('matricule', 'LIKE', "%{$search}%");
            });
        }

        $eleves = $query->orderBy('nom')->get();

        $dernierMatricule = Eleve::where('matricule', 'LIKE', '2026-EP-%')
            ->selectRaw('MAX(CAST(SUBSTRING(matricule, 9) AS UNSIGNED)) as max_num')
            ->value('max_num');

        $prochainMatricule = '2026-EP-' . str_pad(($dernierMatricule ?? 0) + 1, 3, '0', STR_PAD_LEFT);

        return response()->json([
            'eleves' => $eleves,
            'classes' => Classe::orderBy('nom_classe')->get(),
            'prochain_matricule' => $prochainMatricule,
        ]);
    }

    /**
     * Création d'un élève.
     * POST /api/eleves
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'classes_id' => 'required|exists:classes,id',
            'matricule' => 'required|string|unique:eleves,matricule',
        ]);

        $eleve = Eleve::create($validated);
        $eleve->load('classe');

        return response()->json([
            'message' => "Élève inscrit avec succès !",
            'eleve' => $eleve,
        ], 201);
    }

    /**
     * Modification d'un élève.
     * PUT /api/eleves/{id}
     */
    public function update(Request $request, $id)
    {
        $eleve = Eleve::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'classes_id' => 'required|exists:classes,id',
        ]);

        $eleve->update($validated);
        $eleve->load('classe');

        return response()->json([
            'message' => "Informations de l'élève mises à jour avec succès !",
            'eleve' => $eleve,
        ]);
    }

    /**
     * Suppression d'un élève (et de ses notes/paiements liés).
     * DELETE /api/eleves/{id}
     */
    public function destroy($id)
    {
        $eleve = Eleve::findOrFail($id);

        $eleve->paiements()->delete();
        $eleve->notes()->delete();
        $eleve->delete();

        return response()->json([
            'message' => "L'élève a été retiré du registre avec succès.",
        ]);
    }
}
