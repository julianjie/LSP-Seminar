<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    /**
     * Handle login request
     */
public function login(Request $request)
{
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // Cari akun berdasarkan email
    $user = User::where('email', $request->email)->first();

    // Periksa email dan password terlebih dahulu
    if (!$user || !Hash::check($request->password, $user->password)) {
        return back()
            ->withErrors([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ])
            ->onlyInput('email');
    }

    // Periksa status peserta sebelum membuat session login
    if ($user->role === 'participant') {
        if ($user->account_status === 'pending') {
            return back()
                ->withErrors([
                    'email' => 'Akun Anda belum disetujui oleh admin. Silakan tunggu verifikasi.',
                ])
                ->with('error_status', 'pending')
                ->onlyInput('email');
        }

        if ($user->account_status === 'rejected') {
            return back()
                ->withErrors([
                    'email' => 'Akun Anda ditolak oleh admin. Silakan hubungi admin untuk informasi lebih lanjut.',
                ])
                ->with('error_status', 'rejected')
                ->onlyInput('email');
        }
    }

    // Login hanya jika akun memang diperbolehkan masuk
    Auth::login($user, $request->boolean('remember'));

    // Regenerasi session setelah berhasil login
    $request->session()->regenerate();

    return $this->redirectBasedOnRole($user)
        ->with(
            'success',
            'Selamat datang kembali, ' . $user->name . '!'
        );
}

    /**
     * Show registration form
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:15',
            'institution' => 'required|string|max:255',
        ], [
            'email.unique' => 'Email sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password.min' => 'Password minimal terdiri dari 8 karakter.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'participant',
            'account_status' => 'pending',
            'phone' => $request->phone,
            'institution' => $request->institution,
        ]);

        return redirect()->route('login')->with('success_register', 'Pendaftaran akun berhasil! Silakan tunggu verifikasi dari administrator sebelum Anda dapat login.');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }

    /**
     * Redirect logic based on role
     */
    private function redirectBasedOnRole($user)
    {
        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }
        return redirect()->intended(route('participant.dashboard'));
    }
}
