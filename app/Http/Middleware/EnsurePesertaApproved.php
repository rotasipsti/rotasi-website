<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePesertaApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->role === 'peserta') {
            if (is_null($user->sektor) || !$user->is_approved) {
                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}
