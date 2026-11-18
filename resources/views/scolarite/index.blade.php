@extends('layouts.app')

@section('title', 'Frais de scolarité')

@section('content')
<div class="container my-4" style="max-width: 800px;">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title fw-bold text-success mb-1">Frais de scolarité</h5>
            <p class="text-muted small mb-3">Fixe le montant annuel de la scolarité pour chaque classe (en FCFA). Laisse vide si le montant n'est pas encore fixé.</p>

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

            <form action="{{ route('scolarite.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Niveau</th>
                                <th>Classe</th>
                                <th style="min-width: 160px;">Scolarité (FCFA)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($classes as $classe)
                            <tr>
                                <td><span class="badge bg-primary px-2 py-1">{{ $classe->niveau }}</span></td>
                                <td class="fw-bold">{{ $classe->nom_classe }}</td>
                                <td>
                                    <input type="number" name="montants[{{ $classe->id }}]" class="form-control" min="0" step="500" value="{{ $classe->scolarite !== null ? (int) $classe->scolarite : '' }}" placeholder="ex: 25000">
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted small py-4">Crée d'abord des classes.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($classes->isNotEmpty())
                <button type="submit" class="btn btn-success w-100 fw-bold text-white">Enregistrer les frais</button>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection
