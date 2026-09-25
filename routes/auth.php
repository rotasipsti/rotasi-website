<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('accounts/u/login', function () { return view('auth.u.login'); })->name('login.u');
    Route::post('accounts/u/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('accounts/u/register', function () { return view('auth.u.register'); })->name('register.u');

    Route::prefix('accounts/divisi-acara')->group(function () {
        Route::get('register', function () { return view('auth.acara.register'); })->name('register.acara');
        Route::post('register', [RegisteredUserController::class, 'store']);
    });

    Route::prefix('accounts/admin')->group(function () {
        Route::get('login', function () { return view('auth.admin.login'); })->name('login.admin');
        Route::post('login', [AuthenticatedSessionController::class, 'store']);
    });

    Route::prefix('accounts/divisi-mentor')->group(function () {
        Route::get('register', function () { 
            $sektors = \App\Models\SectorPassword::orderBy('sector_number')->get();
            return view('auth.mentor.register', compact('sektors')); 
        })->name('register.mentor');
        Route::post('register', [RegisteredUserController::class, 'store']);
    });

    Route::prefix('accounts/divisi-keamanan')->group(function () {
        Route::get('register', function () { return view('auth.keamanan.register'); })->name('register.keamanan');
        Route::post('register', [RegisteredUserController::class, 'store']);
    });

    Route::prefix('accounts/panitia')->group(function () {
        Route::get('register', function () { return view('auth.panitia.register'); })->name('register.panitia');
        Route::post('register', [RegisteredUserController::class, 'store']);
    });

    Route::get('forgot-password', [\App\Http\Controllers\Auth\OTPPasswordResetController::class, 'requestForm'])
        ->name('password.request');

    Route::post('forgot-password', [\App\Http\Controllers\Auth\OTPPasswordResetController::class, 'sendOTP'])
        ->name('password.email');

    Route::get('verify-otp', [\App\Http\Controllers\Auth\OTPPasswordResetController::class, 'verifyForm'])
        ->name('password.verify.form');

    Route::post('verify-otp', [\App\Http\Controllers\Auth\OTPPasswordResetController::class, 'verifyOTP'])
        ->name('password.verify');

    Route::get('reset-password', [\App\Http\Controllers\Auth\OTPPasswordResetController::class, 'resetForm'])
        ->name('password.reset.form');

    Route::post('reset-password', [\App\Http\Controllers\Auth\OTPPasswordResetController::class, 'resetPassword'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
