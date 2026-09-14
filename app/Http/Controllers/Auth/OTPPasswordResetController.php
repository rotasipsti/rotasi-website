<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Mail\ResetPasswordOTP;

class OTPPasswordResetController extends Controller
{
    /**
     * Tampilkan form input email untuk request OTP
     */
    public function requestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Generate OTP, simpan ke password_reset_tokens, dan kirim via email
     */
    public function sendOTP(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $email = $request->email;
        $otp = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

        // Hapus token lama jika ada
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Simpan token baru
        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => Hash::make($otp),
            'created_at' => Carbon::now()
        ]);

        // Simpan email ke session persistent selama proses OTP
        session(['otp_email' => $email]);

        // Kirim email
        Mail::to($email)->send(new ResetPasswordOTP($otp));

        // Redirect ke form verifikasi
        return redirect()->route('password.verify.form')
            ->with('status', 'Kode OTP telah dikirim ke email Anda.');
    }

    /**
     * Tampilkan form verifikasi OTP
     */
    public function verifyForm(Request $request)
    {
        // Harus ada email di session
        if (!session('otp_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-otp', [
            'email' => session('otp_email')
        ]);
    }

    /**
     * Validasi OTP yang diinput
     */
    public function verifyOTP(Request $request)
    {
        $email = session('otp_email');
        if (!$email) {
            return redirect()->route('password.request');
        }

        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $resetData = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$resetData || !Hash::check($request->otp, $resetData->token)) {
            throw ValidationException::withMessages([
                'otp' => 'Kode OTP tidak valid.',
            ]);
        }

        // Cek kedaluwarsa (misal 15 menit)
        if (Carbon::parse($resetData->created_at)->addMinutes(15)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            throw ValidationException::withMessages([
                'otp' => 'Kode OTP telah kedaluwarsa.',
            ]);
        }

        // OTP valid, simpan penanda di session bahwa user ini boleh reset password
        session(['reset_password_email' => $email]);
        
        // Hapus session otp_email karena sudah tidak diperlukan
        $request->session()->forget('otp_email');

        return redirect()->route('password.reset.form');
    }

    /**
     * Tampilkan form reset password
     */
    public function resetForm(Request $request)
    {
        if (!session('reset_password_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password');
    }

    /**
     * Lakukan update password
     */
    public function resetPassword(Request $request)
    {
        $email = session('reset_password_email');

        if (!$email) {
            return redirect()->route('password.request');
        }

        $request->validate([
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('password.request');
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
            'login_password_hash' => Hash::make($request->password),
        ])->save();

        // Hapus token
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Hapus session
        $request->session()->forget('reset_password_email');

        return redirect()->route('login')->with('status', 'Kata sandi Anda telah berhasil direset.');
    }
}
