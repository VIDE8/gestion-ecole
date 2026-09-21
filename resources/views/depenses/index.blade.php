@extends('layouts.app')

@section('title', 'Dépenses')

@section('content')
<div class="container" style="max-width: 800px;">

    {{-- Résumé Recettes / Dépenses / Solde --}}
    <div class="row g-3 mb-4">
        <div class="col-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Recettes</p>
                    <p class="fw-bold text-success fs-5 mb-0">{{ number_format($totalRecettes, 0, ',', ' ') }} F CFA</p>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Dépenses</p>
                    <p class="fw-bold text-danger fs-5 mb-0">{{ number_format($totalDepenses, 0, ',', ' ') }} F CFA</p>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Solde</p>
                    <p class="fw-bold fs-5 mb-0 {{ $solde >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($solde, 0, ',', ' ') }} F CFA
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Formulaire d'enregistrement --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="text-danger fw-bold mb-4">Enregistrer une Dépense</h2>

            <form method="POST" action="{{ route('depenses.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Libellé</label>
                    <input type="text" name="libelle" value="{{ old('libelle') }}"
                           placeholder="ex: Achat de fournitures"
                           class="form-control @error('libelle') is-invalid @enderror">
                    @error('libelle') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Bénéficiaire</label>
                    <input type="text" name="beneficiaire" value="{{ old('beneficiaire') }}"
                           placeholder="ex: Librairie Centrale"
                           class="form-control @error('beneficiaire') is-invalid @enderror">
                    @error('beneficiaire') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Montant (FCFA)</label>
                    <input type="number" step="1" min="1" name="montant" value="{{ old('montant') }}"
                           placeholder="ex: 25000"
                           class="form-control @error('montant') is-invalid @enderror">
                    @error('montant') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Date de la dépense</label>
                    <input type="date" name="date_depense" value="{{ old('date_depense', date('Y-m-d')) }}"
                           class="form-control @error('date_depense') is-invalid @enderror">
                    @error('date_depense') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-danger w-100 fw-bold py-2">
                    Valider la dépense
                </button>
            </form>
        </div>
    </div>

    {{-- Historique des dépenses --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="fw-bold mb-3 fs-5">Historique des Dépenses ({{ $depenses->count() }})</h2>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Libellé</th>
                            <th>Bénéficiaire</th>
                            <th>Montant</th>
                            <th>Date</th>
                            <th>Enregistré par</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($depenses as $depense)
                            <tr>
                                <td class="fw-semibold">{{ $depense->libelle }}</td>
                                <td>{{ $depense->beneficiaire }}</td>
                                <td class="text-danger fw-bold">
                                    {{ number_format($depense->montant, 0, ',', ' ') }} F CFA
                                </td>
                                <td>{{ \Carbon\Carbon::parse($depense->date_depense)->format('d/m/Y') }}</td>
                                <td>{{ $depense->enregistrePar->name ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Aucune dépense enregistrée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
