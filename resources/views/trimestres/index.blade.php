@extends('layouts.app')

@section('title', 'Trimestres')

@section('content')
<div class="container my-4">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-success mb-3">Nouveau Trimestre</h5>

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

                        @if($anneesScolaires->isEmpty())
                        <div class="alert alert-warning small py-2">
                            Crée d'abord une <a href="{{ url('/annees-scolaires') }}">année scolaire</a>.
                        </div>
                        @else
                        <form action="{{ route('trimestres.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Année scolaire</label>
                                <select name="annee_scolaire_id" class="form-select" required>
                                    @foreach($anneesScolaires as $annee)
                                    <option value="{{ $annee->id }}">{{ $annee->libelle }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Numéro du trimestre</label>
                                <select name="numero" class="form-select" required>
                                    <option value="1">1er trimestre</option>
                                    <option value="2">2ème trimestre</option>
                                    <option value="3">3ème trimestre</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Date de début</label>
                                <input type="date" name="date_debut" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Date de fin</label>
                                <input type="date" name="date_fin" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100 fw-bold text-white">Enregistrer le trimestre</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Liste des Trimestres</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Année scolaire</th>
                                        <th>Trimestre</th>
                                        <th>Début</th>
                                        <th>Fin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($trimestres as $trimestre)
                                    <tr>
                                        <td class="fw-bold">{{ $trimestre->anneeScolaire->libelle }}</td>
                                        <td><span class="badge bg-info text-dark">{{ $trimestre->numero }}er/ème trimestre</span></td>
                                        <td>{{ $trimestre->date_debut->format('d/m/Y') }}</td>
                                        <td>{{ $trimestre->date_fin->format('d/m/Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted small py-4">Aucun trimestre pour le moment.</td>
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
