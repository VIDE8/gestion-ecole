@extends('layouts.app')

@section('title', 'Gestion des Classes')

@section('content')
<div class="container my-4">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-success mb-3">Nouvelle Classe</h5>

                    @if(session('success'))
                    <div class="alert alert-success small py-2">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('classes.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nom de la classe</label>
                            <input type="text" name="nom_classe" class="form-control" placeholder="ex: Groupe A" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Niveau (Primaire Togo)</label>
                            <select name="niveau" class="form-select" required>
                                <option value="CP1">Cours Préparatoire 1ère année (CP1)</option>
                                <option value="CP2">Cours Préparatoire 2ème année (CP2)</option>
                                <option value="CE1">Cours Élémentaire 1ère année (CE1)</option>
                                <option value="CE2">Cours Élémentaire 2ème année (CE2)</option>
                                <option value="CM1">Cours Moyen 1ère année (CM1)</option>
                                <option value="CM2">Cours Moyen 2ème année (CM2)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100 fw-bold text-white">Enregistrer la classe</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">Liste des Classes</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
    <tr>
        <th>Niveau</th>
        <th>Nom / Section</th>
        <th>Enseignant</th>
        <th class="text-end">Actions</th>
    </tr>
</thead>
<tbody>
    @forelse($classes as $classe)
    <tr>
        <td><span class="badge bg-primary text-white px-2 py-1">{{ $classe->niveau }}</span></td>
        <td class="fw-bold">{{ $classe->nom_classe }}</td>
        <td>
            @if($classe->enseignant)
                {{ $classe->enseignant->name }}
            @else
                <span class="text-muted small fst-italic">Non assigné</span>
            @endif
        </td>
        <td class="text-end">
            <a href="{{ url('/eleves?classe_id=' . $classe->id) }}" class="btn btn-sm btn-outline-secondary">Voir Élèves</a>
            <a href="{{ route('classes.edit', $classe->id) }}" class="btn btn-sm btn-outline-success">Modifier</a>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="4" class="text-center text-muted small py-4">Aucune classe pour le moment.</td>
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
