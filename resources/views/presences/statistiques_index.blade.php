@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Statistiques par classe</h3>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">&larr; Retour</a>
    </div>

    <ul class="list-group">
        @forelse($classes as $classe)
            <li class="list-group-item">
              <a href="{{ route('presences.statistiques', $classe) }}">{{ $classe->niveau }} — {{ $classe->nom_classe }}</a>
            </li>
        @empty
            <li class="list-group-item text-muted">Aucune classe enregistrée.</li>
        @endforelse
    </ul>
</div>
@endsection
