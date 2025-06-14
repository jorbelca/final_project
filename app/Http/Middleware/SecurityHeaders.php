<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Protección contra clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Otras cabeceras de seguridad
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // CSP básico
        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self'; frame-ancestors 'self';"
        );

        return $response;
    }
}
