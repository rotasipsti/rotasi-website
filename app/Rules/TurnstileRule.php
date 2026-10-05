<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class TurnstileRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secretKey = env('TURNSTILE_SECRET_KEY', '1x0000000000000000000000000000000AA'); // Default to testing key

        $http = Http::asForm();
        
        // Abaikan verifikasi SSL jika sedang berjalan di server lokal (development)
        if (app()->environment('local')) {
            $http = $http->withoutVerifying();
        }

        $response = $http->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => $secretKey,
            'response' => $value,
            'remoteip' => request()->ip(),
        ]);

        if (!$response->json('success')) {
            $fail('Verifikasi keamanan Turnstile gagal. Pastikan Anda bukan bot.');
        }
    }
}
