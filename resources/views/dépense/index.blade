@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-6 px-4 space-y-6">

    {{-- Résumé Recettes / Dépenses / Solde --}}
    <div class="grid grid-cols-3 gap-3">
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <p class="text-sm text-gray-500">Recettes</p>
            <p class="text-lg font-bold text-green-600">{{ number_format($totalRecettes, 0, ',', ' ') }} F CFA</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <p class="text-sm text-gray-500">Dépenses</p>
            <p class="text-lg font-bold text-red-600">{{ number_format($totalDepenses, 0, ',', ' ') }} F CFA</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <p class="text-sm text-gray-500">Solde</p>
            <p class="text-lg font-bold {{ $solde >= 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ number_format($solde, 0, ',', ' ') }} F CFA
            </p>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 rounded-lg p-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Formulaire d'enregistrement --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-red-600 text-2xl font-bold mb-4">Enregistrer une Dépense</h2>

        <form method="POST" action="{{ route('depenses.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block font-semibold mb-1">Libellé</label>
                <input type="text" name="libelle" value="{{ old('libelle') }}"
                       placeholder="ex: Achat de fournitures"
                       class="w-full border rounded-lg p-3">
                @error('libelle') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block font-semibold mb-1">Bénéficiaire</label>
                <input type="text" name="beneficiaire" value="{{ old('beneficiaire') }}"
                       placeholder="ex: Librairie Centrale"
                       class="w-full border rounded-lg p-3">
                @error('beneficiaire') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block font-semibold mb-1">Montant (FCFA)</label>
                <input type="number" step="1" min="1" name="montant" value="{{ old('montant') }}"
                       placeholder="ex: 25000"
                       class="w-full border rounded-lg p-3">
                @error('montant') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block font-semibold mb-1">Date de la dépense</label>
                <input type="date" name="date_depense" value="{{ old('date_depense', date('Y-m-d')) }}"
                       class="w-full border rounded-lg p-3">
                @error('date_depense') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg p-4">
                Valider la dépense
            </button>
        </form>
    </div>

    {{-- Historique des dépenses --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Historique des Dépenses ({{ $depenses->count() }})</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2">Libellé</th>
                        <th class="p-2">Bénéficiaire</th>
                        <th class="p-2">Montant</th>
                        <th class="p-2">Date</th>
                        <th class="p-2">Enregistré par</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($depenses as $depense)
                        <tr class="border-t">
                            <td class="p-2 font-semibold">{{ $depense->libelle }}</td>
                            <td class="p-2">{{ $depense->beneficiaire }}</td>
                            <td class="p-2 text-red-600 font-bold">
                                {{ number_format($depense->montant, 0, ',', ' ') }} F CFA
                            </td>
                            <td class="p-2">{{ \Carbon\Carbon::parse($depense->date_depense)->format('d/m/Y') }}</td>
                            <td class="p-2">{{ $depense->enregistrePar->name ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">Aucune dépense enregistrée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
