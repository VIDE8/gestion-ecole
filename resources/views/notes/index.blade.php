@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="row mb-4">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form action="{{ url('/notes') }}" method="GET" class="d-flex gap-2">
                        <input type="text" name="search" class="form-control form-control-lg" placeholder="Rechercher les notes d'un élève par nom ou numéro..." value="{{ request('search', '2026-EP-') }}">
                        <button type="submit" class="btn btn-warning px-4 fw-bold text-dark">Rechercher</button>
                        @if(request('search') && request('search') != '2026-EP-')
                        <a href="{{ url('/notes') }}" class="btn btn-outline-secondary d-flex align-items-center">Effacer</a>
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
                    <h5 class="card-title fw-bold text-warning mb-3">Ajouter une Note</h5>

                    @if(session('success'))
                    <div class="alert alert-success small py-2">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('notes.store') }}" method="POST">
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
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Matière (Primaire Togo)</label>
                            <select name="matiere" class="form-select" required>
                                <option value="Calcul écrit">Calcul écrit / Opérations</option>
                                <option value="Calcul mental">Calcul mental</option>
                                <option value="Dictée">Dictée / Questions</option>
                                <option value="Étude de texte">Étude de texte</option>
                                <option value="Histoire-Géo">Histoire-Géo</option>
                                <option value="EDHC">EDHC</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Note (sur 20)</label>
                            <input type="number" step="0.25" name="valeur" class="form-control" placeholder="ex: 14.5" min="0" max="20" required>
                        </div>
                        <button type="submit" class="btn btn-warning w-100 fw-bold text-dark">Enregistrer la Note</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">Bulletins des Notes ({{ count($notes) }})</h5>
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-hover align-middle">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Élève</th>
                                    <th>Classe</th>
                                    <th>Matière</th>
                                    <th>Note / 20</th>
                                    <th>Appréciation</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($notes as $note)
                                <tr>
                                    <td class="fw-bold text-uppercase">{{ $note->eleve->nom }} <span class="text-capitalize fw-normal">{{ $note->eleve->prenom }}</span> <br> <small class="text-muted">{{ $note->eleve->matricule }}</small></td>
                                    <td><span class="badge bg-secondary text-white px-2 py-1">{{ $note->eleve->classe->niveau ?? 'N/A' }}</span></td>
                                    <td>{{ $note->matiere }}</td>
                                    <td class="fw-bold {{ $note->valeur >= 10 ? 'text-success' : 'text-danger' }}">{{ number_format($note->valeur, 2, ',', ' ') }} / 20</td>
                                    <td>
                                        @if($note->valeur >= 16)
                                        <span class="text-success small fw-bold">Très Bien</span>
                                        @elseif($note->valeur >= 14)
                                        <span class="text-success small fw-bold">Bien</span>
                                        @elseif($note->valeur >= 12)
                                        <span class="text-info small fw-bold">Assez Bien</span>
                                        @elseif($note->valeur >= 10)
                                        <span class="text-warning small fw-bold">Passable</span>
                                        @else
                                        <span class="text-danger small fw-bold">Insuffisant</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('notes.edit', $note->id) }}" class="btn btn-sm btn-warning fw-bold px-3">
                                            Modifier
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted small py-4">Aucune note ne correspond à votre recherche.</td>
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
