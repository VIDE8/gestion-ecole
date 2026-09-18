@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Comportement — Tous les élèves</h3>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">&larr; Retour</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Nouvel enregistrement</h5>
            <form action="{{ route('comportements.store_global') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Élève</label>
                    <select name="eleve_id" class="form-select" required>
                        <option value="" disabled selected>-- Choisir un élève --</option>
                        @foreach($eleves as $eleve)
                            <option value="{{ $eleve->id }}">
                                {{ $eleve->nom }} {{ $eleve->prenom }}
                                @if($eleve->classe) ({{ $eleve->classe->niveau }} - {{ $eleve->classe->nom_classe }}) @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select" required>
                        <option value="incident">Incident</option>
                        <option value="appreciation">Appréciation</option>
                        <option value="sanction">Sanction</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-0">Historique</h5>
        <form method="GET" action="{{ route('comportements.index_global') }}" class="d-flex gap-2">
            <select name="classe_id" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Toutes les classes</option>
                @foreach($classes as $classe)
                    <option value="{{ $classe->id }}" @selected(request('classe_id') == $classe->id)>
                        {{ $classe->niveau }} - {{ $classe->nom_classe }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Date</th>
                <th>Élève</th>
                <th>Classe</th>
                <th>Type</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @forelse($comportements as $c)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($c->date)->format('d/m/Y') }}</td>
                    <td>{{ $c->eleve->nom ?? '—' }} {{ $c->eleve->prenom ?? '' }}</td>
                    <td>{{ $c->classe->niveau ?? '—' }} {{ $c->classe->nom_classe ?? '' }}</td>
                    <td>
                        @if($c->type === 'incident')
                            <span class="badge bg-danger">Incident</span>
                        @elseif($c->type === 'appreciation')
                            <span class="badge bg-success">Appréciation</span>
                        @else
                            <span class="badge bg-warning text-dark">Sanction</span>
                        @endif
                    </td>
                    <td>{{ $c->description }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Aucun enregistrement.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
