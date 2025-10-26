<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Mostrar formulario login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Procesar login y redirigir al foro.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required','string','email'],
            'password' => ['required','string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => __('These credentials do not match our records.'),
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        // Mensaje opcional
        session()->flash('status', '¡Bienvenido/a!');

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Logout → a la portada o donde prefieras.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // o ->route('home')
    }
}