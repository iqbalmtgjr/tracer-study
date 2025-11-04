<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TracerVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->get('tracer_verified')) {
            return redirect()->route('kuesioner.form')
                ->with('error', 'Silakan verifikasi data Anda terlebih dahulu.');
        }

        return $next($request);
    }
}
