<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registre des Élèves — React</title>
    <link href="https://fonts.googleapis.com" rel="stylesheet">
    @viteReactRefresh
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/js/react/main.jsx'])
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
                    <a class="nav-link fw-bold me-3" href="{{ url('/') }}">Gestion des Classes</a>
                    @endif

                    @if(in_array(auth()->user()->role, ['admin', 'comptable']))
                    <a class="nav-link active text-primary fw-bold me-3" href="{{ url('/eleves') }}">Registre des Élèves</a>
                    <a class="nav-link fw-bold me-3" href="{{ url('/paiements') }}">Comptabilité / Paiements</a> @endif

                    @if(in_array(auth()->user()->role, ['admin', 'enseignant']))
                    <a class="nav-link fw-bold me-3" href="{{ url('/notes') }}">Saisie des Notes</a>
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

    {{-- Le composant React (resources/js/react/EleveApp.jsx) est monté ici.
         Il consomme l'API JSON /api/eleves (voir routes/web.php + EleveApiController). --}}
    <div id="eleves-react-root"></div>

</body>

</html>