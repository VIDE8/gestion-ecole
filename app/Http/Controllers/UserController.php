<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public const ROLES = [
        'enseignant' => 'Enseignant',
        'comptable' => 'Comptable',
        'admin' => 'Directeur (admin)',
    ];

    public function index()
    {
        $users = User::orderBy('name')->get();
        $roles = self::ROLES;

        return view('users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'role' => ['required', Rule::in(array_keys(self::ROLES))],
            'password' => 'required|string|min:6|max:100',
        ]);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Compte créé pour ' . $validated['name'] . '.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:6|max:100',
        ]);

        $user->update(['password' => $validated['password']]);

        return redirect()->route('users.index')->with('success', 'Mot de passe de ' . $user->name . ' modifié.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return redirect()->route('users.index')->withErrors(['Tu ne peux pas supprimer ton propre compte.']);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Compte supprimé.');
    }
}
