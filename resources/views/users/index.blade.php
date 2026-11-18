@extends('layouts.app')

@section('title', 'Utilisateurs')

@section('content')
<div class="container my-4">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-success mb-3">Nouveau compte</h5>

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

                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nom complet</label>
                            <input type="text" name="name" class="form-control" placeholder="ex: M. MAMIDI" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Adresse e-mail (identifiant)</label>
                            <input type="email" name="email" class="form-control" placeholder="ex: mamidi@ecole.tg" value="{{ old('email') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Rôle</label>
                            <select name="role" class="form-select" required>
                                @foreach($roles as $valeur => $libelle)
                                <option value="{{ $valeur }}" {{ old('role', 'enseignant') === $valeur ? 'selected' : '' }}>{{ $libelle }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Mot de passe provisoire</label>
                            <input type="text" name="password" class="form-control" minlength="6" autocomplete="off" required>
                            <div class="form-text">Visible pour que tu puisses le transmettre à la personne.</div>
                        </div>
                        <button type="submit" class="btn btn-success w-100 fw-bold text-white">Créer le compte</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">Comptes existants</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nom</th>
                                    <th>E-mail</th>
                                    <th>Rôle</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $u)
                                <tr>
                                    <td class="fw-bold">{{ $u->name }}</td>
                                    <td class="small">{{ $u->email }}</td>
                                    <td><span class="badge bg-primary">{{ $roles[$u->role] ?? $u->role }}</span></td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#mdp{{ $u->id }}">Mot de passe</button>
                                        @if($u->id !== auth()->id())
                                        <form action="{{ route('users.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer le compte de {{ addslashes($u->name) }} ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($users as $u)
<div class="modal fade" id="mdp{{ $u->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('users.password', $u) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h6 class="modal-title fw-bold">Nouveau mot de passe — {{ $u->name }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <input type="text" name="password" class="form-control" minlength="6" autocomplete="off" required placeholder="Au moins 6 caractères">
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success fw-bold text-white">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection
