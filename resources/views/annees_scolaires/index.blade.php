@extends('layouts.app')

@section('title', 'Années Scolaires')

@section('content')
<div class="container my-4">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-success mb-3">Nouvelle Année Scolaire</h5>

                        @if(session('success'))
                        <div class="alert alert-success small py-2">{{ session('success') }}</div>
                        @endif

                        @if($errors->any())
                        <div class="alert alert-danger small py-2">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form action="{{ route('annees_scolaires.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Libellé</label>
                                <input type="text" name="libelle" class="form-control" placeholder="ex: 2026-2027" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Date de début</label>
                                <input type="date" name="date_debut" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Date de fin</label>
                                <input type="date" name="date_fin" class="form-control" required>
                            </div>
                            <div class="form-check mb-3">
                                <input type="checkbox" name="active" value="1" class="form-check-input" id="activeCheck">
                                <label class="form-check-label small" for="activeCheck">Définir comme année active</label>
                            </div>
                            <button type="submit" class="btn btn-success w-100 fw-bold text-white">Enregistrer l'année</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Liste des Années Scolaires</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Libellé</th>
                                        <th>Début</th>
                                        <th>Fin</th>
                                        <th>Statut</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($anneesScolaires as $annee)
                                    <tr>
                                        <td class="fw-bold">{{ $annee->libelle }}</td>
                                        <td>{{ $annee->date_debut->format('d/m/Y') }}</td>
                                        <td>{{ $annee->date_fin->format('d/m/Y') }}</td>
                                        <td>
                                            @if($annee->active)
                                            <span class="badge bg-success">Active</span>
                                            @else
                                            <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if(!$annee->active)
                                            <form action="{{ url('/annees-scolaires/' . $annee->id . '/activer') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success">Activer</button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted small py-4">Aucune année scolaire pour le moment.</td>
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
