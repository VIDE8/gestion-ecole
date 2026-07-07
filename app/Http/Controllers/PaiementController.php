<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Eleve;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    public function index(Request $request)
    {
        $query = Paiement::with('eleve.classe');

        if ($request->has('search') && $request->search != '' && $request->search != '2026-EP-') {
            $search = $request->search;
            $query->whereHas('eleve', function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                    ->orWhere('prenom', 'LIKE', "%{$search}%")
                    ->orWhere('matricule', 'LIKE', "%{$search}%");
            });
        }

        $paiements = $query->get();
        $eleves = Eleve::all();

        return view('paiements.index', compact('paiements', 'eleves'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'eleve_id' => 'required|exists:eleves,id',
            'montant_verse' => 'required|numeric|min:1',
            'date_paiement' => 'required|date',
            'type_frais' => 'required|in:scolarite',
        ]);


        // 1. Vérifier si l'élève a déjà payé ce type de frais
        $paiementExistant = Paiement::where('eleve_id', $request->eleve_id)
            ->where('type_frais', $request->type_frais)
            ->first();

        if ($paiementExistant) {
            // Cumuler le nouveau montant avec l'ancien
            $paiementExistant->montant_verse += $request->montant_verse;
            // Mettre à jour la date avec le versement le plus récent
            $paiementExistant->date_paiement = $request->date_paiement;
            $paiementExistant->save();

            return redirect()->back()->with('success', 'Le montant a été ajouté au paiement existant de l\'élève. Reçu conservé : ' . $paiementExistant->reference_recu);
        }

        // 2. Si aucun paiement existant, on génère une nouvelle référence de reçu
        $dernierPaiement = Paiement::where('reference_recu', 'LIKE', 'REC-2026-%')
            ->orderBy('id', 'desc')
            ->first();

        $prochainNumero = 1;

        if ($dernierPaiement) {
            $parties = explode('-', $dernierPaiement->reference_recu);
            $dernierNombre = end($parties);
            if (is_numeric($dernierNombre)) {
                $prochainNumero = (int)$dernierNombre + 1;
            }
        }

        $referenceAuto = 'REC-2026-' . str_pad($prochainNumero, 3, '0', STR_PAD_LEFT);

        // Enregistrement du premier versement
        Paiement::create([
            'montant_verse' => $request->montant_verse,
            'type_frais' => $request->type_frais,
            'date_paiement' => $request->date_paiement,
            'eleve_id' => $request->eleve_id,
            'reference_recu' => $referenceAuto,
        ]);

        return redirect()->back()->with('success', 'Paiement enregistré avec succès ! Reçu : ' . $referenceAuto);
    }

    // Affiche le formulaire d'édition pour un paiement spécifique
    public function edit($id)
    {
        $paiement = Paiement::with('eleve')->findOrFail($id);
        return view('paiements.edit', compact('paiement'));
    }

    // Applique la modification en base de données
    public function update(Request $request, $id)
    {
        $paiement = Paiement::findOrFail($id);

        $request->validate([
            'montant_verse' => 'required|numeric|gt:0',
            'date_paiement' => 'required|date',
            'type_frais' => 'required|string|in:scolarite', // Nettoyé : 'inscription' a été retiré
        ]);

        $paiement->update([
            'montant_verse' => $request->montant_verse,
            'date_paiement' => $request->date_paiement,
            'type_frais' => $request->type_frais,
        ]);

        return redirect()->route('paiements.index')->with('success', 'Le paiement a été modifié avec succès !');
    }
}
