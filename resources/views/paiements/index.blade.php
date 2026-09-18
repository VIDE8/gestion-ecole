@extends('layouts.app')

@section('title', 'Suivi des Paiements')

@section('content')
<div class="container my-4">
    <div class="row mb-4">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form action="{{ url('/paiements') }}" method="GET" class="d-flex gap-2">
                        <input type="text" name="search" class="form-control form-control-lg" placeholder="Rechercher un reçu par nom ou numéro d'élève..." value="{{ request('search', '2026-EP-') }}">
                        <button type="submit" class="btn btn-danger px-4 fw-bold text-white">Rechercher</button>
                        @if(request('search') && request('search') != '2026-EP-')
                        <a href="{{ url('/paiements') }}" class="btn btn-outline-secondary d-flex align-items-center">Effacer</a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-danger mb-3">Enregistrer un Reçu</h5>

                    @if(session('success'))
                    <div class="alert alert-success small py-2">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('paiements.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Élève</label>
                            <select name="eleve_id" class="form-select" required>
                                <option value="">Choisir un élève...</option>
                                @foreach($eleves as $eleve)
                                <option value="{{ $eleve->id }}">{{ $eleve->matricule }} - {{ $eleve->nom }} {{ $eleve->prenom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="type_frais" value="scolarite">

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Montant Versé (FCFA)</label>
                            <input type="number" name="montant_verse" class="form-control" placeholder="ex: 25000" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Date du versement</label>
                            <input type="date" name="date_paiement" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 fw-bold text-white">Valider l'encaissement</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">Historique des Écolages ({{ count($paiements) }})</h5>
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-hover align-middle">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>N° Reçu</th>
                                    <th>Élève</th>
                                    <th>Montant</th>
                                    <th>Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paiements as $paiement)
                                <tr>
                                    <td class="text-muted small fw-bold">{{ $paiement->reference_recu }}</td>
                                    <td class="fw-bold text-uppercase">{{ $paiement->eleve->nom }} <span class="text-capitalize fw-normal">{{ $paiement->eleve->prenom }}</span> <br> <small class="text-muted">{{ $paiement->eleve->matricule }}</small></td>
                                    <td class="fw-bold text-success">{{ number_format($paiement->montant_verse, 0, ',', ' ') }} F CFA</td>
                                    <td class="small">{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('paiements.edit', $paiement->id) }}" class="btn btn-sm btn-warning fw-bold px-3">
                                            Modifier
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted small py-4">Aucun versement ne correspond à votre recherche.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
