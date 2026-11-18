@extends('layouts.app')

@section('title', 'Registre des Élèves')

@section('head')
    @viteReactRefresh
    @vite(['resources/js/react/main.jsx'])
@endsection

@section('content')
    {{-- Le composant React (resources/js/react/EleveApp.jsx) est monté ici.
         Il consomme l'API JSON /api/eleves (voir routes/web.php + EleveApiController). --}}
    <div id="eleves-react-root"></div>
@endsection
