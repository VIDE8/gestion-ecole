@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Statistiques de présence — {{ $classe->nom_classe }}</h3>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">&larr; Retour</a>
    </div>

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Élève</th>
                <th class="text-center">Absences</th>
                <th class="text-center">Retards</th>
            </tr>
        </thead>
        <tbody>
            @forelse($eleves as $i => $eleve)
                @php
                    $s = $stats[$eleve->id] ?? null;
                    $nbAbs = $s->nb_absences ?? 0;
                @endphp
                <tr class="{{ $nbAbs >= 8 ? 'table-danger' : ($nbAbs >= 5 ? 'table-warning' : '') }}">
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $eleve->nom }} {{ $eleve->prenom }}</td>
                    <td class="text-center">{{ $nbAbs }}</td>
                    <td class="text-center">{{ $s->nb_retards ?? 0 }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Aucun élève dans cette classe.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
