<?php

namespace App\Http\Controllers;

use App\Models\Bewerber;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function show()
    {
        return Inertia::render('AuthPage');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('Email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->PW)) {
            return back()->withErrors(['_' => 'E-Mail oder Passwort ist falsch.']);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    public function register(Request $request)
    {
        $request->validate([
            'email'                 => ['required', 'email', 'unique:users,Email'],
            'password'              => ['required', 'min:8', 'confirmed'],
            'Vorname'               => ['required', 'string', 'max:255'],
            'Name'                  => ['required', 'string', 'max:255'],
            'Gebdatum'              => ['required', 'date'],
            'Strasse'               => ['required', 'string', 'max:255'],
            'Hausnr'                => ['required', 'string', 'max:20'],
            'Plz'                   => ['required', 'digits:5'],
            'Ort'                   => ['required', 'string', 'max:255'],
        ]);

        $user = User::create([
            'Role'  => User::ROLE_BEWERBER,
            'Email' => $request->email,
            'PW'    => Hash::make($request->password),
        ]);

        Bewerber::create([
            'UserID'   => $user->UserID,
            'Name'     => $request->Name,
            'Vorname'  => $request->Vorname,
            'Gebdatum' => $request->Gebdatum,
            'Strasse'  => $request->Strasse,
            'Hausnr'   => $request->Hausnr,
            'Plz'      => (int) $request->Plz,
            'Ort'      => $request->Ort,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
