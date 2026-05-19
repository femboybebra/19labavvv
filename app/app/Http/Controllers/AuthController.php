<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('login', $request->login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['login' => 'Неверный логин или пароль.'])->withInput();
        }

        Auth::login($user, $request->boolean('remember'));

        return redirect('/');
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'            => ['required', 'regex:/^[а-яА-ЯёЁ\s\-]+$/u'],
            'surname'         => ['required', 'regex:/^[а-яА-ЯёЁ\s\-]+$/u'],
            'patronymic'      => ['nullable', 'regex:/^[а-яА-ЯёЁ\s\-]+$/u'],
            'login'           => ['required', 'regex:/^[a-zA-Z0-9\-]+$/', 'unique:users,login'],
            'email'           => ['required', 'email', 'unique:users,email'],
            'password'        => ['required', 'min:6', 'confirmed'],
            'rules'           => ['accepted'],
        ]);

        $user = User::create([
            'name'       => $request->name,
            'surname'    => $request->surname,
            'patronymic' => $request->patronymic,
            'login'      => $request->login,
            'email'      => $request->email,
            'password'   => $request->password,
            'is_admin'   => false,
        ]);

        Auth::login($user);

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
