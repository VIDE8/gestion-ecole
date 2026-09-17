<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trimestres</title>
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
                        <h5 class="card-title fw-bold text-success mb-3">Nouveau Trimestre</h5>

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

                        @if($anneesScolaires->isEmpty())
                        <div class="alert alert-warning small py-2">
                            Crée d'abord une <a href="{{ url('/annees-scolaires') }}">année scolaire</a>.
                        </div>
                        @else
                        <form action="{{ route('trimestres.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Année scolaire</label>
                                <select name="annee_scolaire_id" class="form-select" required>
                                    @foreach($anneesScolaires as $annee)
                                    <option value="{{ $annee->id }}">{{ $annee->libelle }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Numéro du trimestre</label>
                                <select name="numero" class="form-select" required>
                                    <option value="1">1er trimestre</option>
                                    <option value="2">2ème trimestre</option>
                                    <option value="3">3ème trimestre</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Date de début</label>
                                <input type="date" name="date_debut" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Date de fin</label>
                                <input type="date" name="date_fin" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100 fw-bold text-white">Enregistrer le trimestre</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Liste des Trimestres</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Année scolaire</th>
                                        <th>Trimestre</th>
                                        <th>Début</th>
                                        <th>Fin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($trimestres as $trimestre)
                                    <tr>
                                        <td class="fw-bold">{{ $trimestre->anneeScolaire->libelle }}</td>
                                        <td><span class="badge bg-info text-dark">{{ $trimestre->numero }}er/ème trimestre</span></td>
                                        <td>{{ $trimestre->date_debut->format('d/m/Y') }}</td>
                                        <td>{{ $trimestre->date_fin->format('d/m/Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted small py-4">Aucun trimestre pour le moment.</td>
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
