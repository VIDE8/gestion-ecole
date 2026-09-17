<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Années Scolaires</title>
    <link href="https://googleapis.com" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-light" style="padding-top: 85px;">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm px-3 fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold fs-3" href="#" style="font-family: 'Playfair Display', serif; color: #ffc107; letter-spacing: 1px;">
                🏫 C.S. L'AVENIR D'OR
            </a>

            <div class="collapse navbar-collapse d-flex justify-content-between">
                <div class="navbar-nav flex-row align-items-center">
                    @if(auth()->user()->role === 'admin')
                    <a class="nav-link {{ Request::is('/') ? 'active text-success' : '' }} fw-bold me-3" href="{{ url('/') }}">Gestion des Classes</a>
                    <a class="nav-link {{ Request::is('annees-scolaires') ? 'active text-info' : '' }} fw-bold me-3" href="{{ url('/annees-scolaires') }}">Années Scolaires</a>
                    <a class="nav-link {{ Request::is('trimestres') ? 'active text-info' : '' }} fw-bold me-3" href="{{ url('/trimestres') }}">Trimestres</a>
                    @endif

                    @if(in_array(auth()->user()->role, ['admin', 'comptable']))
                    <a class="nav-link {{ Request::is('eleves') ? 'active text-primary' : '' }} fw-bold me-3" href="{{ url('/eleves') }}">Registre des Élèves</a>
                    <a class="nav-link {{ Request::is('paiements') ? 'active text-danger' : '' }} fw-bold me-3" href="{{ url('/paiements') }}">Comptabilité / Paiements</a>
                    @endif

                    @if(in_array(auth()->user()->role, ['admin', 'enseignant']))
                    <a class="nav-link {{ Request::is('notes') ? 'active text-warning' : '' }} fw-bold me-3" href="{{ url('/notes') }}">Saisie des Notes</a>
                    @endif
                </div>

                <div class="navbar-nav align-items-center flex-row">
                    <span class="text-white-50 small me-3 fw-bold">{{ auth()->user()->name }} ({{ strtoupper(auth()->user()->role) }})</span>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light fw-bold">Déconnexion</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-success mb-3">Nouvelle Année Scolaire</h5>

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

                        <form action="{{ route('annees_scolaires.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Libellé</label>
                                <input type="text" name="libelle" class="form-control" placeholder="ex: 2026-2027" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Date de début</label>
                                <input type="date" name="date_debut" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Date de fin</label>
                                <input type="date" name="date_fin" class="form-control" required>
                            </div>
                            <div class="form-check mb-3">
                                <input type="checkbox" name="active" value="1" class="form-check-input" id="activeCheck">
                                <label class="form-check-label small" for="activeCheck">Définir comme année active</label>
                            </div>
                            <button type="submit" class="btn btn-success w-100 fw-bold text-white">Enregistrer l'année</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Liste des Années Scolaires</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Libellé</th>
                                        <th>Début</th>
                                        <th>Fin</th>
                                        <th>Statut</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($anneesScolaires as $annee)
                                    <tr>
                                        <td class="fw-bold">{{ $annee->libelle }}</td>
                                        <td>{{ $annee->date_debut->format('d/m/Y') }}</td>
                                        <td>{{ $annee->date_fin->format('d/m/Y') }}</td>
                                        <td>
                                            @if($annee->active)
                                            <span class="badge bg-success">Active</span>
                                            @else
                                            <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if(!$annee->active)
                                            <form action="{{ url('/annees-scolaires/' . $annee->id . '/activer') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success">Activer</button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted small py-4">Aucune année scolaire pour le moment.</td>
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

</body>

</html>
                        
