<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Dapatkan callback redirect URL yang aman dan sesuai skema.
     */
    protected function getRedirectUrl(): string
    {
        $redirectUrl = config('services.google.redirect');

        if (empty($redirectUrl) || Str::startsWith($redirectUrl, '/')) {
            $redirectUrl = url($redirectUrl ?: '/auth/google/callback');
        }

        // Jika app.url HTTPS atau lingkungan production, pastikan callback URL memakai https://
        if ((app()->environment('production') || Str::startsWith(config('app.url'), 'https://')) && Str::startsWith($redirectUrl, 'http://')) {
            $redirectUrl = 'https://' . substr($redirectUrl, 7);
        }

        return $redirectUrl;
    }

    /**
     * Redirect pengguna ke halaman otorisasi Google OAuth.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        /** @var \Laravel\Socialite\Two\GoogleProvider $driver */
        $driver = Socialite::driver('google');

        return $driver->redirectUrl($this->getRedirectUrl())
            ->redirect();
    }

    /**
     * Tangani callback data pengguna setelah otorisasi Google.
     */
    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        try {
            /** @var \Laravel\Socialite\Two\GoogleProvider $driver */
            $driver = Socialite::driver('google');

            $googleUser = $driver->redirectUrl($this->getRedirectUrl())
                ->user();
        } catch (\Throwable $e) {
            Log::warning('Google OAuth Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error_alert', 'Autentikasi Google dibatalkan atau gagal. Silakan coba lagi.');
        }

        $email = strtolower(trim($googleUser->getEmail() ?? ''));

        if (empty($email)) {
            return redirect()->route('login')->with('error_alert', 'Tidak dapat membaca alamat email dari akun Google Anda.');
        }

        // Cari user yang sudah terdaftar dengan email yang sama
        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

        // Jika email TIDAK terdaftar di sistem ROTASI
        if (!$user) {
            return redirect()->route('login')->with('error_alert', "Akun Google ({$email}) tidak terdaftar di sistem ROTASI. Silakan gunakan email yang sudah terdaftar atau daftar akun ROTASI jika belum memiliki akun.");
        }

        // Validasi persetujuan akun untuk role panitia (non-admin & non-peserta)
        if (!$user->is_approved && !in_array($user->role, ['admin', 'peserta'])) {
            return redirect()->route('login')->with('error_alert', 'Akun Anda belum disetujui. Silahkan hubungi Admin.');
        }

        // Tautkan google_id ke akun jika belum tersimpan
        if ($googleUser->getId() && $user->google_id !== $googleUser->getId()) {
            $user->google_id = $googleUser->getId();
            $user->save();
        }

        // Login user
        Auth::login($user, remember: true);
        $request->session()->regenerate();

        // Redirect ke dashboard sesuai peran
        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard', absolute: false))->with('login_success', true);
        }

        return redirect()->intended(route('dashboard', absolute: false))->with('login_success', true);
    }
}
