<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function index()
    {
        $classes = Classe::all();
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
}
