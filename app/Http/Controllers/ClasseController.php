<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\User;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function index()
    {
        $classes = Classe::with('enseignant')->get();
        return view('classes.index', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_classe' => 'required|string|max:255',
            'niveau' => 'required|string|max:255',
        ]);

        Classe::create($validated);
        return redirect()->back()->with('success', 'Classe créée avec succès !');
    }

    public function edit(Classe $classe)
    {
        $enseignants = User::where('role', 'enseignant')->orderBy('name')->get();
        return view('classes.edit', compact('classe', 'enseignants'));
    }

    public function update(Request $request, Classe $classe)
    {
        $validated = $request->validate([
            'nom_classe' => 'required|string|max:255',
            'niveau' => 'required|string|max:255',
            'enseignant_id' => 'nullable|exists:users,id',
        ]);

        $classe->update($validated);
        return redirect()->route('home')->with('success', 'Classe modifiée avec succès !');
    }
}
