<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier le paiement</title>
    @vite(['resources/sass/app.scss'])
</head>

<body class="bg-light" style="padding-top: 50px;">
    <div class="container" style="max-width: 500px;">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning text-dark fw-bold text-center fs-5">
                Modifier le paiement
            </div>
            <form action="{{ route('paiements.update', $paiement->id) }}" method="POST" class="card-body">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Élève</label>
                    <input type="text" class="form-control bg-light text-uppercase fw-bold" value="{{ $paiement->eleve->nom }} {{ $paiement->eleve->prenom }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Référence du reçu</label>
                    <input type="text" class="form-control bg-light" value="{{ $paiement->reference_recu }}" readonly>
                </div>

                <!-- Champ invisible qui force la valeur scolarite en arrière-plan -->
                <input type="hidden" name="type_frais" value="scolarite">

                <div class="mb-3">
                    <label class="form-label small fw-bold">Montant enregistré (FCFA)</label>
                    <input type="number" name="montant_verse" class="form-control" value="{{ $paiement->montant_verse }}" min="1" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Date du versement</label>
                    <input type="date" name="date_paiement" class="form-control" value="{{ $paiement->date_paiement }}" required>
                </div>

                <div class="d-flex gap-2 pt-2">
                    <a href="{{ route('paiements.index') }}" class="btn btn-secondary w-50 fw-bold">Annuler</a>
                    <button type="submit" class="btn btn-warning w-50 fw-bold">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>