<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = $request->user();
        if (!$user->is_approved && $user->role !== 'admin') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $msg = $user->role === 'peserta' 
                ? 'Akun Anda belum disetujui. Silahkan hubungi Admin.' 
                : 'Akun Anda belum disetujui. Silahkan hubungi Admin.';

            return redirect()->back()->with('error_alert', $msg);
        }

        $request->session()->regenerate();

        if ($request->user()->role === 'admin') {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $role = Auth::user()?->role;

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($role === 'admin') {
            return redirect()->route('login.admin');
        } elseif ($role === 'mentor') {
            return redirect()->route('login.mentor');
        } elseif ($role === 'acara') {
            return redirect()->route('login.acara');
        } elseif ($role === 'keamanan') {
            return redirect()->route('login.keamanan');
        } elseif ($role === 'panitia') {
            return redirect()->route('login.panitia');
        }

        return redirect()->route('login');
    }
}
