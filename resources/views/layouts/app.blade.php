<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'École Primaire')</title>

    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#006a4e">
    <meta name="color-scheme" content="light">
    <link rel="icon" href="/icon-192.png" type="image/png">
    <link rel="apple-touch-icon" href="/icon-192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Avenir d'Or">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CDN Principal -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
        }
        .text-violet { color: #6f42c1 !important; }
    </style>

    @yield('head')
</head>

<body>
    <div id="app">
        @php
            $mesClasses = collect();
            if (auth()->check() && auth()->user()->hasRole('enseignant')) {
                $mesClasses = \App\Models\Classe::where('enseignant_id', auth()->id())->get();
            }
        @endphp

        @php
            $authUser = auth()->user();
            $isAdmin = $authUser && $authUser->hasRole('admin');
            $isComptable = $authUser && $authUser->hasRole('comptable');
            $isEnseignant = $authUser && $authUser->hasRole('enseignant');
            $roleLabels = ['admin' => 'Directeur', 'comptable' => 'Comptable', 'enseignant' => 'Enseignant'];
        @endphp

        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3 mb-4">
            <div class="container">
                <a class="navbar-brand fw-bold text-success" href="{{ url('/') }}">
                    🏫 École Primaire
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        @auth
                            @if($isAdmin)
                                <li class="nav-item">
                                    <a class="nav-link fw-bold {{ request()->routeIs('home', 'classes.*') ? 'text-success' : '' }}" href="{{ route('home') }}">Gestion des Classes</a>
                                </li>
                            @endif

                            @if($isAdmin || $isComptable)
                                <li class="nav-item">
                                    <a class="nav-link fw-bold {{ request()->routeIs('eleves.*') ? 'text-primary' : '' }}" href="{{ route('eleves.index') }}">Registre des Élèves</a>
                                </li>
                            @endif

                            @if($isAdmin)
                                {{-- Scolarité : année, trimestres et frais de scolarité --}}
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle fw-bold {{ request()->routeIs('annees_scolaires.*', 'trimestres.*', 'scolarite.*') ? 'text-success' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Scolarité</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('annees_scolaires.index') }}">Années scolaires</a></li>
                                        <li><a class="dropdown-item" href="{{ route('trimestres.index') }}">Trimestres</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="{{ route('scolarite.index') }}">Frais de scolarité</a></li>
                                    </ul>
                                </li>

                                {{-- Vie scolaire : comportement et présences --}}
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle fw-bold {{ request()->routeIs('comportements.*', 'presences.*') ? 'text-success' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Vie scolaire</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('comportements.index_global') }}">Comportement</a></li>
                                        <li><a class="dropdown-item" href="{{ route('presences.statistiques.index') }}">Statistiques de présence</a></li>
                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link fw-bold {{ request()->routeIs('users.*') ? 'text-success' : '' }}" href="{{ route('users.index') }}">Utilisateurs</a>
                                </li>
                            @endif

                            @if($isComptable)
                                <li class="nav-item">
                                    <a class="nav-link fw-bold {{ request()->routeIs('paiements.*') ? 'text-danger' : '' }}" href="{{ route('paiements.index') }}">Comptabilité / Paiements</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link fw-bold {{ request()->routeIs('depenses.*') ? 'text-violet' : '' }}" href="{{ route('depenses.index') }}">Dépenses</a>
                                </li>
                            @endif

                            @if($isEnseignant)
                                <li class="nav-item">
                                    <a class="nav-link fw-bold {{ request()->routeIs('notes.*') ? 'text-info' : '' }}" href="{{ route('notes.index') }}">Notes</a>
                                </li>

                                @if($mesClasses->isNotEmpty())
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle fw-bold {{ request()->routeIs('comportements.*', 'presences.*') ? 'text-success' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Vie scolaire</a>
                                        <ul class="dropdown-menu">
                                            @foreach($mesClasses as $classe)
                                                @if($mesClasses->count() > 1)
                                                    <li><h6 class="dropdown-header">{{ $classe->niveau }} — {{ $classe->nom_classe }}</h6></li>
                                                @endif
                                                <li><a class="dropdown-item" href="{{ route('presences.appel', $classe) }}">Appel</a></li>
                                                <li><a class="dropdown-item" href="{{ route('comportements.index', $classe) }}">Comportement</a></li>
                                                <li><a class="dropdown-item" href="{{ route('presences.statistiques', $classe) }}">Statistiques</a></li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endif
                            @endif
                        @endauth
                    </ul>

                    @auth
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small">{{ $authUser->name }} <span class="badge bg-secondary">{{ $roleLabels[$authUser->role] ?? strtoupper($authUser->role) }}</span></span>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-secondary">Déconnexion</button>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 JavaScript CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    @yield('scripts')

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/sw.js').catch(function () {});
            });
        }
    </script>
</body>

</html>
