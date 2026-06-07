<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login    = $request->input('username');
        $password = $request->input('password');

        // Coba login via email jika input berbentuk email, lalu fallback ke name
        $isEmail    = filter_var($login, FILTER_VALIDATE_EMAIL);
        $fieldTried = $isEmail ? 'email' : 'name';

        $attempted = Auth::attempt([$fieldTried => $login, 'password' => $password]);

        // Jika gagal dan input adalah email, coba via name juga (atau sebaliknya)
        if (!$attempted) {
            $fallback  = $isEmail ? 'name' : 'email';
            $attempted = Auth::attempt([$fallback => $login, 'password' => $password]);
        }

        if ($attempted) {
            $request->session()->regenerate();
            // Simpan timestamp updated_at untuk deteksi perubahan akun
            $request->session()->put('user_updated_at', Auth::user()->updated_at?->timestamp);
            return redirect()->route('dashboard')->with('success', 'Login berhasil! Selamat datang, ' . Auth::user()->name . '.');
        }

        return back()->withErrors([
            'username' => 'Username / Email atau password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    public function showRegister()
    {
        return view('register');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('logout', 'Anda berhasil logout. Sampai jumpa!');
    }
}
