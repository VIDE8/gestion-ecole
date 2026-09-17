@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Appel — {{ $classe->nom_classe }} <small class="text-muted">({{ now()->format('d/m/Y') }})</small></h3>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">&larr; Retour</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('presences.enregistrer', $classe) }}" method="POST">
        @csrf
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Élève</th>
                    <th class="text-center">Présent</th>
                    <th class="text-center">Absent</th>
                    <th class="text-center">Retard</th>
                </tr>
            </thead>
            <tbody>
                @foreach($eleves as $i => $eleve)
                    @php $statutActuel = $presencesDuJour[$eleve->id] ?? 'present'; @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $eleve->nom }} {{ $eleve->prenom }}</td>
                        @foreach(['present' => 'success', 'absent' => 'danger', 'retard' => 'warning'] as $valeur => $couleur)
                            <td class="text-center">
                                <input type="radio"
                                       class="form-check-input"
                                       name="statuts[{{ $eleve->id }}]"
                                       value="{{ $valeur }}"
                                       {{ $statutActuel === $valeur ? 'checked' : '' }}>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Enregistrer l'appel</button>
    </form>
</div>
@endsection
