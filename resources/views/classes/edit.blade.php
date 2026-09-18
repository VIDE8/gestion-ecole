@extends('layouts.app')

@section('title', 'Modifier la classe')

@section('content')
<div class="container" style="max-width: 500px;">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white fw-bold text-center fs-5">
            Modifier la classe
        </div>
        <form action="{{ route('classes.update', $classe->id) }}" method="POST" class="card-body">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label small fw-bold">Nom de la classe</label>
                <input type="text" name="nom_classe" class="form-control" value="{{ $classe->nom_classe }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold">Niveau (Primaire Togo)</label>
                <select name="niveau" class="form-select" required>
                    @foreach(['CP1', 'CP2', 'CE1', 'CE2', 'CM1', 'CM2'] as $niveau)
                    <option value="{{ $niveau }}" {{ $classe->niveau === $niveau ? 'selected' : '' }}>{{ $niveau }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold">Enseignant titulaire</label>
                <select name="enseignant_id" class="form-select">
                    <option value="">Aucun enseignant assigné</option>
                    @foreach($enseignants as $enseignant)
                    <option value="{{ $enseignant->id }}" {{ $classe->enseignant_id == $enseignant->id ? 'selected' : '' }}>{{ $enseignant->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex gap-2 pt-2">
                <a href="{{ route('home') }}" class="btn btn-secondary w-50 fw-bold">Annuler</a>
                <button type="submit" class="btn btn-success w-50 fw-bold">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection
