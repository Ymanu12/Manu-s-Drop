<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function edit()
    {
        return view('settings');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'L\'ancien mot de passe est incorrect.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Mot de passe mis à jour avec succès.');
    }

    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        // Vérifier si le mot de passe est correct
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        // Supprimer l'utilisateur
        $user->delete();

        // Déconnecter l'utilisateur
        Auth::logout();

        return redirect('/')->with('success', 'Votre compte a été supprimé avec succès.');
    }

    // Dans votre controller ou vue
    public function updateTheme(Request $request)
    {
        $user = Auth::user();
        $user->theme = $request->theme;  // dark ou light
        $user->save();

        // Enregistrez le thème dans la session pour qu'il soit accessible côté front-end
        session(['theme' => $request->theme]);

        return redirect()->route('settings');
    }
}
