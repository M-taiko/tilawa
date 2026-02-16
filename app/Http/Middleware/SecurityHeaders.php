<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent clickjacking attacks
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Enable XSS protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Control referrer information
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Content Security Policy - more permissive in local for Vite
        if (app()->environment('local', 'development')) {
            // Relaxed CSP for development with Vite
            $csp = "default-src * 'unsafe-inline' 'unsafe-eval' data: blob:; " .
                "script-src * 'unsafe-inline' 'unsafe-eval'; " .
                "style-src * 'unsafe-inline'; " .
                "img-src * data: blob: 'unsafe-inline'; " .
                "font-src * data:; " .
                "connect-src * ws: wss:;";
        } else {
            // Strict CSP for production
            $csp = "default-src 'self'; " .
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com; " .
                "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com; " .
                "font-src 'self' https://fonts.gstatic.com; " .
                "img-src 'self' data: https:; " .
                "connect-src 'self';";
        }

        $response->headers->set('Content-Security-Policy', $csp);

        // Prevent browsers from performing MIME type sniffing
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

        // Strict Transport Security (HSTS) - only in production
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
