<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Tampilkan form registrasi
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi user baru
     */
    public function register(Request $r)
    {
        $r->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|min:6",
        ]);

        // Default role = user
        $user = User::create([
            'name' => $r->name,
            'email' => $r->email,
            'password' => Hash::make($r->password),
            'phone' => $r->phone,
            'address' => $r->address,
            'role' => 'user', // ✅ Tambahkan agar konsisten dengan migration ENUM
        ]);

        Auth::login($user);

        return $this->redirectByRole($user);
    }

    /**
     * Tampilkan form login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses login user
     */
    public function login(Request $r)
    {
        $credentials = $r->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $r->session()->regenerate();
            $user = Auth::user();

            // ✅ Arahkan user berdasarkan rolenya
            return $this->redirectByRole($user);
        }

        return back()->withErrors(['email' => 'Email atau password salah'])->withInput();
    }

    /**
     * Forgot password sederhana: generate password baru dan tampilkan di halaman login.
     */
    public function forgotPassword(Request $r)
    {
        $r->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $r->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan'])->withInput();
        }

        $newPassword = Str::random(8);
        $user->password = Hash::make($newPassword);
        $user->save();

        return back()->with('new_password', $newPassword)->with('email', $r->email);
    }

    /**
     * Logout user
     */
    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * 🔹 Fungsi bantu untuk redirect berdasarkan role
     */
    private function redirectByRole($user)
    {
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.nota.index'); // admin ke menu nota
            case 'kasir':
                return redirect()->route('admin.nota.index'); // kasir ke menu nota
            default:
                return redirect()->route('home');
        }
    }
}
