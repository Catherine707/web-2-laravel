<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register()
    {
        $validated = $this->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Hash de contraseña
        $validated['password'] = Hash::make($validated['password']);


        $user = User::create($validated);
        event(new Registered($user));

        // Iniciar sesión y regenerar sesión
        Auth::login($user);
        session()->regenerate();

        // Redirigir al foro (o a la intended)
        return redirect()->intended(route('questions.index'));

    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}